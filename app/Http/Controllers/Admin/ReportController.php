<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Business;
use App\Models\User;
use App\Models\Review;
use App\Models\AnalyticsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function systemUsage(Request $request)
    {
        $periodParam = $request->get('period', '30days');

        // Extract numeric value from period string
        $period = match ($periodParam) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            default => 30
        };

        $stats = [
            'total_page_views' => AnalyticsLog::where('event_type', 'page_view')
                ->where('created_at', '>=', now()->subDays($period))
                ->count(),

            'unique_visitors' => AnalyticsLog::where('event_type', 'page_view')
                ->where('created_at', '>=', now()->subDays($period))
                ->distinct('ip_address')
                ->count('ip_address'),

            'registered_users' => User::where('created_at', '>=', now()->subDays($period))
                ->count(),

            'total_searches' => AnalyticsLog::where('event_type', 'search')
                ->where('created_at', '>=', now()->subDays($period))
                ->count(),
        ];

        // Daily page views
        $dailyViews = AnalyticsLog::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays($period))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Most visited pages
        $topPages = AnalyticsLog::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays($period))
            ->select('page', DB::raw('COUNT(*) as count'))
            ->groupBy('page')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.reports.system-usage', compact('stats', 'dailyViews', 'topPages', 'period'));
    }


    public function attractionAnalytics(Request $request)
    {
        $period = $request->get('period', 30);

        $topAttractions = Attraction::approved()
            ->select('id', 'name', 'views', 'category_id')
            ->with('category')
            ->withCount(['reviews as total_reviews' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->withAvg(['reviews as average_rating' => function ($q) {
                $q->where('status', 'approved');
            }], 'rating')
            ->orderByDesc('views')
            ->limit(20)
            ->get();

        // Category distribution
        $categoryStats = Attraction::approved()
            ->select('category_id', DB::raw('COUNT(*) as count'))
            ->with('category')
            ->groupBy('category_id')
            ->get();

        return view('admin.reports.attraction-analytics', compact('topAttractions', 'categoryStats', 'period'));
    }

    public function businessAnalytics()
    {
        $stats = [
            'total_businesses' => Business::approved()->count(),
            'verified_businesses' => Business::verified()->count(),
            'businesses_with_reviews' => Business::has('approvedReviews')->count(),
        ];

        $topBusinesses = Business::approved()
            ->select('id', 'name', 'views', 'business_category_id')
            ->with('category')
            ->withCount('approvedReviews')
            ->withAvg(['approvedReviews as average_rating'], 'rating')
            ->orderByDesc('views')
            ->limit(20)
            ->get();

        return view('admin.reports.business-analytics', compact('stats', 'topBusinesses'));
    }

    public function userDemographics()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'business_owners' => User::where('role', 'business_owner')->count(),
            'active_users' => User::where('is_active', true)->count(),
            'users_with_reviews' => User::has('reviews')->count(),
        ];

        // User registration trend
        $registrationTrend = User::where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.user-demographics', compact('stats', 'registrationTrend'));
    }

    // PDF Generation Methods
    public function systemUsagePdf(Request $request)
    {
        $periodParam = $request->get('period', '30days');
        $period = match ($periodParam) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            default => 30
        };

        $stats = [
            'total_page_views' => AnalyticsLog::where('event_type', 'page_view')
                ->where('created_at', '>=', now()->subDays($period))
                ->count(),
            'unique_visitors' => AnalyticsLog::where('event_type', 'page_view')
                ->where('created_at', '>=', now()->subDays($period))
                ->distinct('ip_address')
                ->count('ip_address'),
            'registered_users' => User::where('created_at', '>=', now()->subDays($period))
                ->count(),
            'total_searches' => AnalyticsLog::where('event_type', 'search')
                ->where('created_at', '>=', now()->subDays($period))
                ->count(),
        ];

        $dailyViews = AnalyticsLog::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays($period))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topPages = AnalyticsLog::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays($period))
            ->select('page', DB::raw('COUNT(*) as count'))
            ->groupBy('page')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.system-usage', compact('stats', 'dailyViews', 'topPages', 'period'));
        return $pdf->download('system-usage-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function attractionAnalyticsPdf(Request $request)
    {
        $period = $request->get('period', 30);

        $topAttractions = Attraction::approved()
            ->select('id', 'name', 'views', 'category_id')
            ->with('category')
            ->withCount(['reviews as total_reviews' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->withAvg(['reviews as average_rating' => function ($q) {
                $q->where('status', 'approved');
            }], 'rating')
            ->orderByDesc('views')
            ->limit(20)
            ->get();

        $categoryStats = Attraction::approved()
            ->select('category_id', DB::raw('COUNT(*) as count'))
            ->with('category')
            ->groupBy('category_id')
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.attraction-analytics', compact('topAttractions', 'categoryStats', 'period'));
        return $pdf->download('attraction-analytics-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function businessAnalyticsPdf()
    {
        $stats = [
            'total_businesses' => Business::approved()->count(),
            'verified_businesses' => Business::verified()->count(),
            'businesses_with_reviews' => Business::has('approvedReviews')->count(),
        ];

        $topBusinesses = Business::approved()
            ->select('id', 'name', 'views', 'business_category_id')
            ->with('category')
            ->withCount('approvedReviews')
            ->withAvg(['approvedReviews as average_rating'], 'rating')
            ->orderByDesc('views')
            ->limit(20)
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.business-analytics', compact('stats', 'topBusinesses'));
        return $pdf->download('business-analytics-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function userDemographicsPdf()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'business_owners' => User::where('role', 'business_owner')->count(),
            'active_users' => User::where('is_active', true)->count(),
            'users_with_reviews' => User::has('reviews')->count(),
        ];

        $registrationTrend = User::where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf.user-demographics', compact('stats', 'registrationTrend'));
        return $pdf->download('user-demographics-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
