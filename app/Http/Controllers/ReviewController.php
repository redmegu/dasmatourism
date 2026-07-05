<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Detect if a review contains suspicious content
     */
    private function isSuspiciousReview(?string $comment): bool
    {
        if (!$comment) {
            return false;
        }

        // Basic profanity list (extend anytime)
        $blockedWords = config('moderation.blocked_words', []);

        foreach ($blockedWords as $word) {
            if (stripos($comment, $word) !== false) {
                return true;
            }
        }

        // Detect URLs / spam links
        if (preg_match('/https?:\/\/|www\./i', $comment)) {
            return true;
        }

        // Detect excessive repeated symbols (!!!!!, $$$$$)
        if (preg_match('/(.)\1{4,}/', $comment)) {
            return true;
        }

        return false;
    }

    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|in:App\Models\Attraction,App\Models\Business',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate reviews
        $existingReview = Review::where('user_id', auth()->id())
            ->where('reviewable_type', $request->reviewable_type)
            ->where('reviewable_id', $request->reviewable_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already submitted a review for this.');
        }

        $isSuspicious = $this->isSuspiciousReview($request->comment);

        $reviewData = [
    'user_id' => auth()->id(),
    'reviewable_type' => $request->reviewable_type,
    'reviewable_id' => $request->reviewable_id,
    'rating' => $request->rating,
    'comment' => $request->comment,
    'status' => $isSuspicious ? 'pending' : 'approved',
    'needs_approval' => $isSuspicious,
];

if (!$isSuspicious) {
    $reviewData['approved_at'] = now();
    $reviewData['approved_by'] = null; // System auto-approval
}

Review::create($reviewData);

        // Log user review activity
        $placeType = class_basename($request->reviewable_type);
        $user = auth()->user();
        ActivityLog::logActivity(
            'review',
            "{$user->name} posted a review on {$placeType} (ID: {$request->reviewable_id})" .
                ($isSuspicious ? ' [pending approval]' : ''),
            $request->reviewable_type,
            $request->reviewable_id
        );

        return back()->with(
            'success',
            $isSuspicious
                ? 'Your review was submitted and is pending admin review.'
                : 'Thank you! Your review is now live.'
        );
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, Review $review)
    {
        // Ensure user owns the review
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $isSuspicious = $this->isSuspiciousReview($request->comment);

        $reviewData = [
    'rating' => $request->rating,
    'comment' => $request->comment,
    'status' => $isSuspicious ? 'pending' : 'approved',
    'needs_approval' => $isSuspicious,
];

if (!$isSuspicious) {
    $reviewData['approved_at'] = now();
    $reviewData['approved_by'] = null;
} else {
    $reviewData['approved_at'] = null;
    $reviewData['approved_by'] = null;
}

$review->update($reviewData);

        return back()->with(
            'success',
            $isSuspicious
                ? 'Your updated review is pending admin approval.'
                : 'Your review has been updated successfully.'
        );
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        // Ensure user owns the review
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Your review has been deleted.');
    }
}
