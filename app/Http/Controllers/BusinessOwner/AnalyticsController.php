<?php

namespace App\Http\Controllers\BusinessOwner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\UserInteraction;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $business = auth()->user()->business;

        if (!$business) {
            return redirect()->route('business-owner.profile.create')
                ->with('info', 'Please create your business profile first.');
        }

        // Total Views
        $totalViews = $business->views ?? 0;

        // Views growth (compared to last month)
        $lastMonthViews = UserInteraction::where('interactable_type', 'App\Models\Business')
            ->where('interactable_id', $business->id)
            ->where('interaction_type', 'view')
            ->whereBetween('created_at', [now()->subMonths(2)->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        $viewsGrowth = $lastMonthViews > 0 ? round((($totalViews - $lastMonthViews) / $lastMonthViews) * 100, 1) : 0;

        // Average Rating
        $averageRating = $business->getAverageRating();

        // Total Reviews
        $totalReviews = $business->approvedReviews()->count();

        // Reviews this month
        $reviewsThisMonth = Review::where('reviewable_type', 'App\Models\Business')
            ->where('reviewable_id', $business->id)
            ->where('status', 'approved')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        // Active Promotions
        $activePromotions = $business->promotions()->active()->count();

        // Total Promotion Views
        $totalPromotionViews = $business->promotions()->sum('views');

        // Views Chart (last 30 days)
        $viewsChart = UserInteraction::where('interactable_type', 'App\Models\Business')
            ->where('interactable_id', $business->id)
            ->where('interaction_type', 'view')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Rating Distribution (initialize all ratings 1-5)
        $ratingDistribution = collect([5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]);

        $ratings = Review::where('reviewable_type', 'App\Models\Business')
            ->where('reviewable_id', $business->id)
            ->where('status', 'approved')
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating');

        foreach ($ratings as $rating => $count) {
            $ratingDistribution[$rating] = $count;
        }

        // Recent Reviews
        $recentReviews = Review::where('reviewable_type', 'App\Models\Business')
            ->where('reviewable_id', $business->id)
            ->where('status', 'approved')
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Peak Day
        $peakDayData = UserInteraction::where('interactable_type', 'App\Models\Business')
            ->where('interactable_id', $business->id)
            ->where('interaction_type', 'view')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DAYNAME(created_at) as day'), DB::raw('COUNT(*) as count'))
            ->groupBy('day')
            ->orderByDesc('count')
            ->first();

        $peakDay = $peakDayData ? $peakDayData->day : 'N/A';

        // Average Views Per Day
        $avgViewsPerDay = $viewsChart->count() > 0 ? $viewsChart->avg('count') : 0;

        // Total Reviewers (unique users)
        $totalReviewers = Review::where('reviewable_type', 'App\Models\Business')
            ->where('reviewable_id', $business->id)
            ->where('status', 'approved')
            ->distinct('user_id')
            ->count('user_id');

        return view('business-owner.analytics', compact(
            'business',
            'totalViews',
            'viewsGrowth',
            'averageRating',
            'totalReviews',
            'reviewsThisMonth',
            'activePromotions',
            'totalPromotionViews',
            'viewsChart',
            'ratingDistribution',
            'recentReviews',
            'peakDay',
            'avgViewsPerDay',
            'totalReviewers'
        ));
    }
}
