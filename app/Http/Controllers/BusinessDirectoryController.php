<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\UserInteraction;
use App\Models\AnalyticsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::approved()->with(['category', 'primaryImage']);

        // Search FIRST (most specific filter)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });

            // Log the search
            $this->logSearch($search, 'businesses');
        }

        // Filter by category (only if specified)
        if ($request->filled('category')) {
            $query->where('business_category_id', $request->category);
        }

        // Filter by verified
        if ($request->filled('verified') && $request->verified == 1) {
            $query->where('is_verified', true);
        }

        // ── Sorting ───────────────────────────────────────────────────────
        // Subquery: avg rating and review count per business, approved only.
        // Only businesses with >= 2 approved reviews are given a real score;
        // listings with 0 or 1 review get NULL avg / 0 count so they fall to
        // the bottom when sorting by rating.
        $reviewSub = DB::table('reviews')
            ->select(
                'reviewable_id',
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as review_count')
            )
            ->where('reviewable_type', \App\Models\Business::class)
            ->where('status', 'approved')
            ->groupBy('reviewable_id')
            ; // no minimum review count

        $query->leftJoinSub($reviewSub, 'rev', function ($join) {
            $join->on('businesses.id', '=', 'rev.reviewable_id');
        })->addSelect('businesses.*', 'rev.avg_rating', 'rev.review_count');

        $sortBy = $request->input('sort_by', 'rating_count'); // default

        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('businesses.name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('businesses.name', 'desc');
                break;

            case 'rating_count':
                // Most reviewed first; nulls (< 2 reviews) last
                $query->orderByRaw('review_count IS NULL ASC')
                      ->orderBy('review_count', 'desc')
                      ->orderBy('businesses.name', 'asc');
                break;

            case 'rating_score':
            default:
                // Highest average first; nulls (< 2 reviews) last
                $query->orderByRaw('avg_rating IS NULL ASC')
                      ->orderBy('avg_rating', 'desc')
                      ->orderBy('review_count', 'desc')
                      ->orderBy('businesses.name', 'asc');
                break;
        }
        // ─────────────────────────────────────────────────────────────────

        $businesses = $query->paginate(12)->withQueryString();

        // Load bookmarks for authenticated users
        if (auth()->check()) {
            $businesses->load(['bookmarks' => function ($query) {
                $query->where('user_id', auth()->id());
            }]);
        }

        $categories = BusinessCategory::where('is_active', true)
            ->withCount('businesses')
            ->get();

        return view('businesses.index', compact('businesses', 'categories'));
    }

    public function show($slug)
    {
        $business = Business::where('slug', $slug)
            ->approved()
            ->with(['category', 'images', 'owner', 'approvedReviews.user'])
            ->firstOrFail();

        // Increment views
        $business->incrementViews();

        // Log interaction
        if (auth()->check()) {
            UserInteraction::create([
                'user_id' => auth()->id(),
                'interactable_type' => Business::class,
                'interactable_id' => $business->id,
                'interaction_type' => 'view',
            ]);
        }

        // Get active promotions for this business (use the active() scope)
        $activePromotions = $business->promotions()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        $relatedBusinesses = Business::approved()
            ->where('business_category_id', $business->business_category_id)
            ->where('id', '!=', $business->id)
            ->with('primaryImage')
            ->limit(4)
            ->get();

        return view('businesses.show', compact('business', 'relatedBusinesses', 'activePromotions'));
    }


    /**
     * Log search queries to analytics
     */
    protected function logSearch($query, $type = 'general')
    {
        try {
            AnalyticsLog::create([
                'event_type' => 'search',
                'page' => request()->path(),
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => json_encode([
                    'search_query' => $query,
                    'search_type' => $type,
                    'timestamp' => now()->toDateTimeString(),
                ]),
            ]);
        } catch (\Exception $e) {
            // Silently fail to not break the application
            \Log::error('Search logging failed: ' . $e->getMessage());
        }
    }
}
