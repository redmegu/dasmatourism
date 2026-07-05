<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Category;
use App\Models\AttractionImage;
use App\Models\AttractionSchedule;
use App\Models\MapMarker;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttractionController extends Controller
{
    public function index(Request $request)
    {
        $query = Attraction::with(['category', 'creator', 'primaryImage']);

        // Only filter by status if it's provided AND not "all"
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Only filter by category if it's provided AND not "all"
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $attractions = $query->latest()->paginate(15)->appends($request->all());
        $categories = Category::all();

        return view('admin.attractions.index', compact('attractions', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.attractions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:0,1000',
            'longitude' => 'required|numeric|between:0,1000',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'google_maps_link' => 'nullable|url',
            'youtube_video_url' => 'nullable|url',
            'uploaded_video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400',
            'entrance_fee' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|string',
            'commute_guide' => 'nullable|string',
            'is_historical_site' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'approved';

        // Check if slug already exists
        if (Attraction::where('slug', $validated['slug'])->exists()) {
            return back()->withInput()->with('error', 'An attraction with this name already exists. Please use a different name.');
        }

        try {
            // Handle video upload
            if ($request->hasFile('uploaded_video')) {
                $validated['uploaded_video_path'] = $request->file('uploaded_video')->store('attractions/videos', 'public');
            }

            $attraction = Attraction::create($validated);

            // Handle images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('attractions', 'public');

                    AttractionImage::create([
                        'attraction_id' => $attraction->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'order' => $index,
                    ]);
                }
            }

            // Handle schedules
            if ($request->has('schedules')) {
                foreach ($request->schedules as $schedule) {
                    if (isset($schedule['day_of_week'])) {
                        AttractionSchedule::create([
                            'attraction_id' => $attraction->id,
                            'day_of_week' => $schedule['day_of_week'],
                            'opening_time' => $schedule['opening_time'] ?? '09:00',
                            'closing_time' => $schedule['closing_time'] ?? '17:00',
                            'is_closed' => $schedule['is_closed'] ?? false,
                        ]);
                    }
                }
            }

            // Create map marker
            MapMarker::create([
                'attraction_id' => $attraction->id,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'marker_color' => $validated['is_historical_site'] ?? false ? '#8B4513' : '#FF0000',
            ]);

            ActivityLog::logActivity(
                'create',
                "Created attraction: {$attraction->name}",
                Attraction::class,
                $attraction->id
            );

            return redirect()->route('admin.attractions.index')
                ->with('success', 'Attraction created successfully.');
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return back()->withInput()->with('error', 'An attraction with this name already exists. Please use a different name.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred while creating the attraction. Please try again.');
        }
    }

    public function show(Attraction $attraction)
    {
        $attraction->load(['category', 'images', 'schedules', 'marker', 'reviews.user']);
        return view('admin.attractions.show', compact('attraction'));
    }

    public function edit(Attraction $attraction)
    {
        $categories = Category::where('is_active', true)->get();
        $attraction->load(['images', 'schedules']);
        return view('admin.attractions.edit', compact('attraction', 'categories'));
    }

    public function update(Request $request, Attraction $attraction)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:0,1000',
            'longitude' => 'required|numeric|between:0,1000',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'google_maps_link' => 'nullable|url',
            'youtube_video_url' => 'nullable|url',
            'uploaded_video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400',
            'entrance_fee' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|string',
            'commute_guide' => 'nullable|string',
            'is_historical_site' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:pending,approved,rejected',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Check if slug already exists (excluding current attraction)
        if (Attraction::where('slug', $validated['slug'])->where('id', '!=', $attraction->id)->exists()) {
            return back()->withInput()->with('error', 'An attraction with this name already exists. Please use a different name.');
        }

        try {
            // Handle video deletion if requested
            if ($request->has('delete_uploaded_video') && $attraction->uploaded_video_path) {
                Storage::disk('public')->delete($attraction->uploaded_video_path);
                $validated['uploaded_video_path'] = null;
                $validated['youtube_video_url'] = null;
            }

            // Handle new video upload
            if ($request->hasFile('uploaded_video')) {
                // Delete old video if exists
                if ($attraction->uploaded_video_path) {
                    Storage::disk('public')->delete($attraction->uploaded_video_path);
                }
                $validated['uploaded_video_path'] = $request->file('uploaded_video')->store('attractions/videos', 'public');
                // Clear YouTube URL when uploading video
                $validated['youtube_video_url'] = null;
            }

            // Handle YouTube URL update
            if ($request->filled('youtube_video_url')) {
                // Delete uploaded video if YouTube URL is provided
                if ($attraction->uploaded_video_path) {
                    Storage::disk('public')->delete($attraction->uploaded_video_path);
                    $validated['uploaded_video_path'] = null;
                }
            }

            // If YouTube URL is cleared, make sure to set it to null
            if (!$request->filled('youtube_video_url') && !$request->hasFile('uploaded_video')) {
                $validated['youtube_video_url'] = null;
            }

            $attraction->update($validated);

            // Handle new images
            if ($request->hasFile('images')) {
                $currentImagesCount = $attraction->images()->count();

                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('attractions', 'public');

                    AttractionImage::create([
                        'attraction_id' => $attraction->id,
                        'image_path' => $path,
                        'is_primary' => $currentImagesCount === 0 && $index === 0,
                        'order' => $currentImagesCount + $index,
                    ]);
                }
            }

            // Update map marker
            if ($attraction->marker) {
                $attraction->marker->update([
                    'latitude' => $validated['latitude'],
                    'longitude' => $validated['longitude'],
                    'marker_color' => $validated['is_historical_site'] ?? false ? '#8B4513' : '#FF0000',
                ]);
            }

            // Update primary image
            if ($request->filled('primary_image_id')) {
                $attraction->images()->update(['is_primary' => false]);
                $attraction->images()->where('id', $request->primary_image_id)->update(['is_primary' => true]);
            }

            ActivityLog::logActivity(
                'update',
                "Updated attraction: {$attraction->name}",
                Attraction::class,
                $attraction->id
            );

            return redirect()->route('admin.attractions.index')
                ->with('success', 'Attraction updated successfully.');
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return back()->withInput()->with('error', 'An attraction with this name already exists. Please use a different name.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred while updating the attraction. Please try again.');
        }
    }

    public function destroy(Attraction $attraction)
    {
        // Delete images from storage
        foreach ($attraction->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete uploaded video if exists
        if ($attraction->uploaded_video_path) {
            Storage::disk('public')->delete($attraction->uploaded_video_path);
        }

        $attractionName = $attraction->name;
        $attraction->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted attraction: {$attractionName}",
            Attraction::class,
            $attraction->id
        );

        return redirect()->route('admin.attractions.index')
            ->with('success', 'Attraction deleted successfully.');
    }

    public function deleteImage($id)
    {
        $image = AttractionImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
