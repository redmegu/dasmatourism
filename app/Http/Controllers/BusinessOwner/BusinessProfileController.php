<?php

namespace App\Http\Controllers\BusinessOwner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessImage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BusinessProfileController extends Controller
{
    public function show()
    {
        $business = auth()->user()->business;

        if (!$business) {
            return redirect()->route('business-owner.profile.create');
        }

        $business->load(['category', 'images', 'approvedReviews.user']);

        return view('business-owner.profile.show', compact('business'));
    }

    public function create()
    {
        if (auth()->user()->business) {
            return redirect()->route('business-owner.profile.show');
        }

        $categories = BusinessCategory::where('is_active', true)->get();
        return view('business-owner.profile.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->business) {
            return redirect()->route('business-owner.profile.show')
                ->with('error', 'You already have a business profile.');
        }

        $validated = $request->validate([
            'business_category_id' => 'required|exists:business_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'google_maps_link' => 'nullable|url',
            'services' => 'nullable|string',  // Accept as string
            'operating_hours' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'business_permit' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['owner_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = 'pending';

        // Convert services string to array
        if ($validated['services']) {
            $validated['services'] = array_filter(
                array_map('trim', explode("\n", $validated['services']))
            );
        } else {
            $validated['services'] = [];
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('businesses/logos', 'public');
        }

        // Handle business permit upload (REQUIRED)
        if ($request->hasFile('business_permit')) {
            $validated['business_permit'] = $request->file('business_permit')->store('businesses/permits', 'public');
        }

        $business = Business::create($validated);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('businesses', 'public');

                BusinessImage::create([
                    'business_id' => $business->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'order' => $index,
                ]);
            }
        }

        ActivityLog::logActivity(
            'create',
            "Created business profile: {$business->name}",
            Business::class,
            $business->id
        );

        return redirect()->route('business-owner.dashboard')
            ->with('success', 'Business profile created successfully and is pending approval.');
    }

    public function edit()
    {
        $business = auth()->user()->business;

        if (!$business) {
            return redirect()->route('business-owner.profile.create');
        }

        $categories = BusinessCategory::where('is_active', true)->get();
        $business->load('images');

        return view('business-owner.profile.edit', compact('business', 'categories'));
    }

    public function update(Request $request)
    {
        $business = auth()->user()->business;

        if (!$business) {
            return redirect()->route('business-owner.profile.create');
        }

        $validated = $request->validate([
            'business_category_id' => 'required|exists:business_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'google_maps_link' => 'nullable|url',
            'services' => 'nullable|string',  // Accept as string
            'operating_hours' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'business_permit' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Convert services string to array
        if ($validated['services']) {
            $validated['services'] = array_filter(
                array_map('trim', explode("\n", $validated['services']))
            );
        } else {
            $validated['services'] = [];
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($business->logo) {
                Storage::disk('public')->delete($business->logo);
            }
            $validated['logo'] = $request->file('logo')->store('businesses/logos', 'public');
        }

        // Handle business permit upload (optional on edit, resets verification)
        if ($request->hasFile('business_permit')) {
            if ($business->business_permit) {
                Storage::disk('public')->delete($business->business_permit);
            }
            $validated['business_permit'] = $request->file('business_permit')->store('businesses/permits', 'public');
            $validated['permit_verified_at'] = null; // Reset verification
        }

        $business->update($validated);

        // Handle new images
        if ($request->hasFile('images')) {
            $currentImagesCount = $business->images()->count();

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('businesses', 'public');

                BusinessImage::create([
                    'business_id' => $business->id,
                    'image_path' => $path,
                    'is_primary' => $currentImagesCount === 0 && $index === 0,
                    'order' => $currentImagesCount + $index,
                ]);
            }
        }

        ActivityLog::logActivity(
            'update',
            "Updated business profile: {$business->name}",
            Business::class,
            $business->id
        );

        return redirect()->route('business-owner.profile.show')
            ->with('success', 'Business profile updated successfully.');
    }

    public function deleteImage($id)
    {
        $image = BusinessImage::findOrFail($id);

        if ($image->business->owner_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        ActivityLog::logActivity(
            'delete_image',
            "Deleted business image from: {$image->business->name}",
            BusinessImage::class,
            $image->id
        );

        return response()->json(['success' => true]);
    }

    public function preview()
    {
        $business = auth()->user()->business;

        if (!$business) {
            return redirect()->route('business-owner.profile.create');
        }

        // If approved, redirect to public page
        if ($business->status === 'approved') {
            return redirect()->route('businesses.show', $business->slug);
        }

        // Show status page for pending/rejected
        return view('business-owner.profile.preview', compact('business'));
    }
}
