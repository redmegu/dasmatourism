<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
{
    $query = Review::with(['user', 'reviewable']);

    /**
     * ✅ Status Filter Logic
     */
    if ($request->filled('status')) {

        if ($request->status === 'flagged') {
            // Only reviews needing admin attention
            $query->where('needs_approval', true);

        } elseif ($request->status !== 'all') {
            // Approved / Rejected / Pending
            $query->where('status', $request->status);
        }

        // ✅ If status = all → DO NOTHING (no filter)
    }

    // ✅ Type filter
    if ($request->filled('type')) {
        if ($request->type === 'attraction') {
            $query->where('reviewable_type', 'App\Models\Attraction');
        } elseif ($request->type === 'business') {
            $query->where('reviewable_type', 'App\Models\Business');
        }
    }

    // ✅ Search filter
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
            })
            ->orWhereHasMorph(
                'reviewable',
                ['App\Models\Attraction', 'App\Models\Business'],
                function ($placeQuery) use ($search) {
                    $placeQuery->where('name', 'like', "%{$search}%");
                }
            )
            ->orWhere('comment', 'like', "%{$search}%");
        });
    }

    $reviews = $query
        ->latest()
        ->paginate(20)
        ->withQueryString();

    return view('admin.reviews.index', compact('reviews'));
}
    



    public function approve(Review $review)
    {
        $review->update([
            'status' => 'approved',
            'needs_approval' => false,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLog::logActivity(
            'approve_review',
            "Approved review by {$review->user->name}",
            Review::class,
            $review->id
        );

        return back()->with('success', 'Review approved and published.');
    }

    public function reject(Review $review)
    {
        $review->update([
            'status' => 'rejected',
            'needs_approval' => false,
            'approved_by' => auth()->id(),
        ]);

        ActivityLog::logActivity(
            'reject_review',
            "Rejected review by {$review->user->name}",
            Review::class,
            $review->id
        );

        return back()->with('success', 'Review rejected successfully.');
    }

    public function destroy(Review $review)
    {
        $userName = $review->user->name;

        $review->delete();

        ActivityLog::logActivity(
            'delete_review',
            "Deleted review by {$userName}",
            Review::class,
            $review->id
        );

        return back()->with('success', 'Review deleted successfully.');
    }
    public function approveAll()
{
    $reviews = Review::where('needs_approval', true)
        ->where('status', 'pending')
        ->get();

    if ($reviews->isEmpty()) {
        return back()->with('info', 'No reviews to approve.');
    }

    foreach ($reviews as $review) {
        $review->update([
            'status' => 'approved',
            'needs_approval' => false,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        ActivityLog::logActivity(
            'approve_review',
            "Approved review by {$review->user->name} (bulk action)",
            Review::class,
            $review->id
        );
    }

    return back()->with('success', "{$reviews->count()} reviews approved successfully.");
}
    public function rejectAll()
{
    $reviews = Review::where('needs_approval', true)
        ->where('status', 'pending')
        ->get();

    if ($reviews->isEmpty()) {
        return back()->with('info', 'No reviews to reject.');
    }

    foreach ($reviews as $review) {
        $review->update([
            'status' => 'rejected',
            'needs_approval' => false,
            'approved_by' => auth()->id(),
        ]);

        ActivityLog::logActivity(
            'reject_review',
            "Rejected review by {$review->user->name} (bulk action)",
            Review::class,
            $review->id
        );
    }

    return back()->with('success', "{$reviews->count()} reviews rejected successfully.");
}
    public function bulkApprove(Request $request)
{
    Review::whereIn('id', $request->ids ?? [])
        ->update([
            'status' => 'approved',
            'needs_approval' => false,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

    return back()->with('success', 'Selected reviews approved.');
}

public function bulkReject(Request $request)
{
    Review::whereIn('id', $request->ids ?? [])
        ->update([
            'status' => 'rejected',
            'needs_approval' => false,
            'approved_by' => auth()->id(),
        ]);

    return back()->with('success', 'Selected reviews rejected.');
}



}
