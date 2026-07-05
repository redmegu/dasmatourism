<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Attraction;
use App\Models\Business;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::with(['promotable', 'creator']);

        if ($request->has('type')) {
            if ($request->type === 'attraction') {
                $query->where('promotable_type', Attraction::class);
            } elseif ($request->type === 'business') {
                $query->where('promotable_type', Business::class);
            }
        }

        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $promotions = $query->latest()->paginate(15);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $attractions = Attraction::approved()->get();
        $businesses = Business::approved()->get();

        return view('admin.promotions.create', compact('attractions', 'businesses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'promotable_type' => 'required|in:App\Models\Attraction,App\Models\Business',
            'promotable_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('promotions', 'public');
        }

        $validated['created_by'] = auth()->id();

        $promotion = Promotion::create($validated);

        ActivityLog::logActivity(
            'create',
            "Created promotion: {$promotion->title}",
            Promotion::class,
            $promotion->id
        );

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    public function show(Promotion $promotion)
    {
        $promotion->load(['promotable', 'creator']);
        return view('admin.promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        $attractions = Attraction::approved()->get();
        $businesses = Business::approved()->get();

        return view('admin.promotions.edit', compact('promotion', 'attractions', 'businesses'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'promotable_type' => 'required|in:App\Models\Attraction,App\Models\Business',
            'promotable_id' => 'required|integer',
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

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
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

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }
}
