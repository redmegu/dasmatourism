<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $user->load('profile', 'bookmarks', 'reviews', 'interactions');

        return view('user.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        $user->load('profile');

        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Update user basic info
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update profile
        $profileData = [
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'gender' => $validated['gender'] ?? null,
        ];

        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            if ($user->profile && $user->profile->profile_picture) {
                Storage::disk('public')->delete($user->profile->profile_picture);
            }
            $profileData['profile_picture'] = $request->file('profile_picture')
                ->store('profiles', 'public');
        }

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            ActivityLog::logActivity(
                'update',
                "Updated profile and changed password",
                get_class($user),
                $user->id
            );
        } else {
            ActivityLog::logActivity(
                'update',
                "Updated profile information",
                get_class($user),
                $user->id
            );
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    // NEW METHODS ↓↓↓

    public function bookmarks()
    {
        $user = auth()->user();
        $bookmarks = $user->bookmarks()->with('bookmarkable')->latest()->paginate(12);

        return view('user.bookmarks.index', compact('bookmarks'));
    }

    public function toggleBookmark(Request $request, $type, $id)
    {
        $user = auth()->user();
        $modelClass = $type === 'attraction' ? 'App\Models\Attraction' : 'App\Models\Business';

        $bookmark = $user->bookmarks()
            ->where('bookmarkable_type', $modelClass)
            ->where('bookmarkable_id', $id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return back()->with('success', 'Bookmark removed.');
        } else {
            $newBookmark = $user->bookmarks()->create([
                'bookmarkable_type' => $modelClass,
                'bookmarkable_id' => $id,
            ]);
            return back()->with('success', 'Bookmark added.');
        }
    }

    public function reviews(Request $request)
    {
        $user = auth()->user();
        $query = $user->reviews()->with('reviewable');

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Type filter (attraction or business)
        if ($request->filled('type')) {
            if ($request->type === 'attraction') {
                $query->where('reviewable_type', 'App\Models\Attraction');
            } elseif ($request->type === 'business') {
                $query->where('reviewable_type', 'App\Models\Business');
            }
        }

        // Search filter (place name or comment)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Search in reviewable (place) name
                $q->whereHasMorph('reviewable', ['App\Models\Attraction', 'App\Models\Business'], function ($placeQuery) use ($search) {
                    $placeQuery->where('name', 'like', "%{$search}%");
                })
                    // Search in comment
                    ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->latest()->paginate(12)->withQueryString();

        return view('user.reviews.index', compact('reviews'));
    }
}
