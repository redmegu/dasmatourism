<?php

namespace App\Http\Controllers\BusinessOwner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Review;
use App\Models\UserInteraction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $business = auth()->user()->business;

        if (!$business) {
            return redirect()->route('business-owner.profile.create')
                ->with('info', 'Please create your business profile first.');
        }

        $stats = [
            'total_views' => $business->views,
            'total_reviews' => $business->approvedReviews()->count(),
            'average_rating' => $business->getAverageRating(),
            'active_promotions' => $business->promotions()->active()->count(),
        ];

        // Recent reviews
        $recentReviews = $business->reviews()
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Views trend (last 30 days)
        $viewsTrend = UserInteraction::where('interactable_type', Business::class)
            ->where('interactable_id', $business->id)
            ->where('interaction_type', 'view')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('business-owner.dashboard', compact('business', 'stats', 'recentReviews', 'viewsTrend'));
    }
}
