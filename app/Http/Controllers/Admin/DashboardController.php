<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Business;
use App\Models\User;
use App\Models\Review;
use App\Models\LandmarkSuggestion;
use App\Models\AnalyticsLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_attractions' => Attraction::count(),
            'approved_attractions' => Attraction::where('status', 'approved')->count(),
            'pending_attractions' => Attraction::where('status', 'pending')->count(),
            'total_businesses' => Business::count(),
            'approved_businesses' => Business::where('status', 'approved')->count(),
            'pending_businesses' => Business::where('status', 'pending')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'business_owners' => User::where('role', 'business_owner')->count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'pending_suggestions' => LandmarkSuggestion::where('status', 'pending')->count(),
        ];

        // Recent activities
        $recentAttractions = Attraction::with('category')->latest()->limit(5)->get();
        $recentReviews = Review::with(['user', 'reviewable'])->latest()->limit(5)->get();
        $recentSuggestions = LandmarkSuggestion::with('user')->latest()->limit(5)->get();

        // Analytics - Last 30 days
        $pageViews = AnalyticsLog::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Popular attractions
        $popularAttractions = Attraction::approved()
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentAttractions',
            'recentReviews',
            'recentSuggestions',
            'pageViews',
            'popularAttractions'
        ));
    }
}
