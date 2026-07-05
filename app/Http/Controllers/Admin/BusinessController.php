<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessImage;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with(['category', 'owner', 'primaryImage']);

        // Only filter by status if it's provided AND not "all"
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Only filter by category if it's provided AND not "all"
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('business_category_id', $request->category);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $businesses = $query->latest()->paginate(15)->appends($request->all());
        $categories = BusinessCategory::all();

        return view('admin.businesses.index', compact('businesses', 'categories'));
    }

    public function create()
    {
        $categories = BusinessCategory::where('is_active', true)->get();
        $owners = User::where('role', 'business_owner')->orderBy('name')->get();

        return view('admin.businesses.create', compact('categories', 'owners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_category_id' => 'required|exists:business_categories,id',
            'owner_id'             => 'nullable|exists:users,id',
            'name'                 => 'required|string|max:255',
            'description'          => 'required|string',
            'address'              => 'required|string',
            'contact_number'       => 'required|string|max:20',
            'email'                => 'nullable|email',
            'website'              => 'nullable|url',
            'google_maps_link'     => 'nullable|url',
            'services'             => 'nullable|string',
            'operating_hours'      => 'nullable|string',
            'logo'                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'business_permit'      => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'images.*'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'               => 'required|in:pending,approved,rejected',
            'is_verified'          => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Convert services string to array
        if (!empty($validated['services'])) {
            $validated['services'] = array_values(array_filter(
                array_map('trim', explode("\n", $validated['services']))
            ));
        } else {
            $validated['services'] = [];
        }

        $validated['is_verified'] = $request->boolean('is_verified');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('businesses/logos', 'public');
        }

        // Handle business permit upload
        if ($request->hasFile('business_permit')) {
            $validated['business_permit'] = $request->file('business_permit')->store('businesses/permits', 'public');
            if ($validated['is_verified']) {
                $validated['permit_verified_at'] = now();
            }
        }

        $business = Business::create($validated);

        // Handle gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('businesses', 'public');
                BusinessImage::create([
                    'business_id' => $business->id,
                    'image_path'  => $path,
                    'is_primary'  => $index === 0,
                    'order'       => $index,
                ]);
            }
        }

        ActivityLog::logActivity(
            'create',
            "Admin created business: {$business->name} (status: {$business->status})",
            Business::class,
            $business->id
        );

        return redirect()->route('admin.businesses.show', $business)
            ->with('success', "Business \"{$business->name}\" created successfully.");
    }

    public function show(Business $business)
    {
        $business->load(['category', 'owner', 'images', 'reviews.user']);
        return view('admin.businesses.show', compact('business'));
    }

    public function updateStatus(Request $request, Business $business)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $business->update(['status' => $request->status]);

        ActivityLog::logActivity(
            'update_status',
            "Changed business '{$business->name}' status to: {$request->status}",
            Business::class,
            $business->id
        );

        return back()->with('success', 'Business status updated successfully.');
    }

    public function toggleVerification(Business $business)
    {
        $business->update(['is_verified' => !$business->is_verified]);

        $action = $business->is_verified ? 'verified' : 'unverified';
        ActivityLog::logActivity(
            'toggle_verification',
            "Business '{$business->name}' {$action}",
            Business::class,
            $business->id
        );

        return back()->with('success', 'Business verification status updated.');
    }

    public function verifyPermit(Business $business)
    {
        if (!$business->business_permit) {
            return back()->with('error', 'No business permit found to verify.');
        }

        $business->update(['permit_verified_at' => now()]);

        ActivityLog::logActivity(
            'verify_permit',
            "Verified business permit for: {$business->name}",
            Business::class,
            $business->id
        );

        return back()->with('success', 'Business permit verified successfully.');
    }

    public function rejectPermit(Business $business)
    {
        $business->update(['permit_verified_at' => null]);

        ActivityLog::logActivity(
            'reject_permit',
            "Rejected business permit for: {$business->name}",
            Business::class,
            $business->id
        );

        return back()->with('success', 'Business permit verification removed.');
    }

    public function destroy(Business $business)
    {
        $businessName = $business->name;
        $business->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted business: {$businessName}",
            Business::class,
            $business->id
        );

        return redirect()->route('admin.businesses.index')
            ->with('success', 'Business deleted successfully.');
    }
}
