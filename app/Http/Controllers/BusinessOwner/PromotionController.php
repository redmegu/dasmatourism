<?php

namespace App\Http\Controllers\BusinessOwner;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    /**
     * Ensure business exists and is approved
     */
    private function getApprovedBusiness()
    {
        $business = auth()->user()->business;

        if (!$business) {
            redirect()->route('business-owner.profile.create')->send();
            exit;
        }

        if ($business->status !== 'approved') {
            abort(403, 'Your business must be approved before managing promotions.');
        }

        return $business;
    }

    public function index()
    {
        $business = $this->getApprovedBusiness();

        $promotions = $business->promotions()->latest()->paginate(10);

        return view('business-owner.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $this->getApprovedBusiness();

        return view('business-owner.promotions.create');
    }

    public function store(Request $request)
    {
        $business = $this->getApprovedBusiness();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('promotions', 'public');
        }

        $validated['promotable_type'] = 'App\\Models\\Business';
        $validated['promotable_id'] = $business->id;
        $validated['created_by'] = auth()->id();
        $validated['is_active'] = true;
        $validated['views'] = 0;

        $promotion = Promotion::create($validated);

        ActivityLog::logActivity(
            'create',
            "Created promotion: {$promotion->title} for business: {$business->name}",
            Promotion::class,
            $promotion->id
        );

        return redirect()
            ->route('business-owner.promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    public function edit(Promotion $promotion)
    {
        $business = $this->getApprovedBusiness();

        if ($promotion->promotable_id !== $business->id) {
            abort(403);
        }

        return view('business-owner.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $business = $this->getApprovedBusiness();

        if ($promotion->promotable_id !== $business->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($promotion->image) {
                Storage::disk('public')->delete($promotion->image);
            }
            $validated['image'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion->update($validated);

        ActivityLog::logActivity(
            'update',
            "Updated promotion: {$promotion->title}",
            Promotion::class,
            $promotion->id
        );

        return redirect()
            ->route('business-owner.promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $business = $this->getApprovedBusiness();

        if ($promotion->promotable_id !== $business->id) {
            abort(403);
        }

        if ($promotion->image) {
            Storage::disk('public')->delete($promotion->image);
        }

        $promotionTitle = $promotion->title;
        $promotion->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted promotion: {$promotionTitle}",
            Promotion::class,
            $promotion->id
        );

        return redirect()
            ->route('business-owner.promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }
}
