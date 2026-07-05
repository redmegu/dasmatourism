<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LandmarkSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandmarkSuggestionController extends Controller
{
    public function index()
    {
        $suggestions = LandmarkSuggestion::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.suggestions.index', compact('suggestions'));
    }

    public function create()
    {
        return view('user.suggestions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_historical' => 'nullable|boolean',
            'category_suggestion' => 'nullable|string|max:255',
            'reason' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';
        $validated['is_historical'] = $request->has('is_historical');

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('suggestions', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        $suggestion = LandmarkSuggestion::create($validated);

        ActivityLog::logActivity(
            'create',
            "Submitted landmark suggestion: {$suggestion->name}",
            LandmarkSuggestion::class,
            $suggestion->id
        );

        return redirect()->route('user.suggestions.index')
            ->with('success', 'Your landmark suggestion has been submitted for review.');
    }

    public function show(LandmarkSuggestion $suggestion)
    {
        // Ensure user can only view their own suggestions
        if ($suggestion->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.suggestions.show', compact('suggestion'));
    }

    public function edit(LandmarkSuggestion $suggestion)
    {
        // Ensure user can only edit their own suggestions
        if ($suggestion->user_id !== auth()->id()) {
            abort(403);
        }

        // Only allow editing if pending or rejected
        if (!in_array($suggestion->status, ['pending', 'rejected'])) {
            return redirect()->route('user.suggestions.index')
                ->with('error', 'You cannot edit an approved suggestion.');
        }

        return view('user.suggestions.edit', compact('suggestion'));
    }

    public function update(Request $request, LandmarkSuggestion $suggestion)
    {
        // Ensure user can only update their own suggestions
        if ($suggestion->user_id !== auth()->id()) {
            abort(403);
        }

        // Only allow updating if pending or rejected
        if (!in_array($suggestion->status, ['pending', 'rejected'])) {
            return redirect()->route('user.suggestions.index')
                ->with('error', 'You cannot edit an approved suggestion.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_historical' => 'nullable|boolean',
            'reason' => 'nullable|string',
        ]);

        $validated['is_historical'] = $request->has('is_historical');
        $validated['status'] = 'pending'; // Reset to pending after edit

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($suggestion->images && is_array($suggestion->images)) {
                foreach ($suggestion->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('suggestions', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        $suggestion->update($validated);

        ActivityLog::logActivity(
            'update',
            "Updated landmark suggestion: {$suggestion->name}",
            LandmarkSuggestion::class,
            $suggestion->id
        );

        return redirect()->route('user.suggestions.index')
            ->with('success', 'Your suggestion has been updated and is pending review.');
    }

    public function destroy(LandmarkSuggestion $suggestion)
    {
        // Ensure user can only delete their own suggestions
        if ($suggestion->user_id !== auth()->id()) {
            abort(403);
        }

        // Only allow deleting if not approved
        if ($suggestion->status === 'approved') {
            return redirect()->route('user.suggestions.index')
                ->with('error', 'You cannot delete an approved suggestion.');
        }

        // Delete associated images
        if ($suggestion->images && is_array($suggestion->images)) {
            foreach ($suggestion->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $suggestionName = $suggestion->name;
        $suggestion->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted landmark suggestion: {$suggestionName}",
            LandmarkSuggestion::class,
            $suggestion->id
        );

        return redirect()->route('user.suggestions.index')
            ->with('success', 'Your suggestion has been deleted.');
    }
}
