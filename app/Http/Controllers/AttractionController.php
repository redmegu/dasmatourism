<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Category;
use App\Models\UserInteraction;
use App\Models\AnalyticsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttractionController extends Controller
{
    public function index(Request $request)
    {
        $query = Attraction::approved()->with(['category', 'primaryImage']);

        // Search FIRST (most specific filter)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });

            // Log the search
            $this->logSearch($search, 'attractions');
        }

        // Filter by category (only if specified)
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by historical sites
        if ($request->filled('historical') && $request->historical == 1) {
            $query->where('is_historical_site', true);
        }

        // ── Sorting ───────────────────────────────────────────────────────
        // Subquery: avg rating and review count per attraction, approved only.
        // Only attractions with >= 2 approved reviews are given a real score;
        // listings with 0 or 1 review get NULL avg / 0 count so they fall to
        // the bottom when sorting by rating.
        $reviewSub = DB::table('reviews')
            ->select(
                'reviewable_id',
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as review_count')
            )
            ->where('reviewable_type', \App\Models\Attraction::class)
            ->where('status', 'approved')
            ->groupBy('reviewable_id')
            ; // no minimum review count

        $query->leftJoinSub($reviewSub, 'rev', function ($join) {
            $join->on('attractions.id', '=', 'rev.reviewable_id');
        })->addSelect('attractions.*', 'rev.avg_rating', 'rev.review_count');

        $sortBy = $request->input('sort_by', 'rating_count'); // default

        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('attractions.name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('attractions.name', 'desc');
                break;

            case 'rating_count':
                // Most reviewed first; nulls (< 2 reviews) last
                $query->orderByRaw('review_count IS NULL ASC')
                      ->orderBy('review_count', 'desc')
                      ->orderBy('attractions.name', 'asc');
                break;

            case 'rating_score':
            default:
                // Highest average first; nulls (< 2 reviews) last
                $query->orderByRaw('avg_rating IS NULL ASC')
                      ->orderBy('avg_rating', 'desc')
                      ->orderBy('review_count', 'desc')
                      ->orderBy('attractions.name', 'asc');
                break;
        }
        // ─────────────────────────────────────────────────────────────────

        $attractions = $query->paginate(12)->withQueryString();

        // Load bookmarks for authenticated users
        if (auth()->check()) {
            $attractions->load(['bookmarks' => function ($query) {
                $query->where('user_id', auth()->id());
            }]);
        }

        $categories = Category::where('is_active', true)
            ->withCount('attractions')
            ->get();

        return view('attractions.index', compact('attractions', 'categories'));
    }

    public function show($slug)
    {
        $attraction = Attraction::where('slug', $slug)
            ->approved()
            ->with(['category', 'images', 'schedules', 'approvedReviews.user'])
            ->firstOrFail();

        // Increment views
        $attraction->incrementViews();

        // Log interaction
        if (auth()->check()) {
            UserInteraction::create([
                'user_id' => auth()->id(),
                'interactable_type' => Attraction::class,
                'interactable_id' => $attraction->id,
                'interaction_type' => 'view',
            ]);
        }

        $relatedAttractions = Attraction::approved()
            ->where('category_id', $attraction->category_id)
            ->where('id', '!=', $attraction->id)
            ->with('primaryImage')
            ->limit(4)
            ->get();

        return view('attractions.show', compact('attraction', 'relatedAttractions'));
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
