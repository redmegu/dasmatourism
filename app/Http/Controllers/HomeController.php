<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attraction;
use App\Models\Business;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Promotion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch featured and historical attractions
        $featuredAttractions = Attraction::approved()
            ->featured()
            ->with(['category', 'primaryImage'])
            ->limit(6)
            ->get();

        $historicalSites = Attraction::approved()
            ->historicalSites()
            ->with(['category', 'primaryImage'])
            ->limit(4)
            ->get();

        // Fetch categories with attraction counts
        $categories = Category::where('is_active', true)
            ->withCount('attractions')
            ->get();

        // Fetch active promotions
        $activePromotions = Promotion::active()
            ->with('promotable')
            ->latest()
            ->limit(5)
            ->get();

        // Total approved businesses count
        $businessesCount = Business::approved()->count();

        // Add announcements
        $announcements = Announcement::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();

        // Hero carousel slides
        $heroSlides = HeroSlide::active()->get();

        // Hotels & Accommodations: find the attraction Category whose name
        // contains "hotel" or "accommodation", then pull approved Attractions
        // from it ordered by most reviewed.
        $hotelsCategory = Category::where('is_active', true)
            ->where(function ($q) {
                $q->where('name', 'like', '%hotel%')
                  ->orWhere('name', 'like', '%accommodation%');
            })
            ->first();

        $hotelListings = collect();
        if ($hotelsCategory) {
            $hotelListings = Attraction::approved()
                ->where('category_id', $hotelsCategory->id)
                ->with(['primaryImage'])
                ->withCount(['approvedReviews'])
                ->withAvg('approvedReviews', 'rating')
                ->orderBy('approved_reviews_count', 'desc')
                ->limit(6)
                ->get();
        }

        return view('home', compact(
            'featuredAttractions',
            'historicalSites',
            'categories',
            'activePromotions',
            'announcements',
            'businessesCount',
            'heroSlides',
            'hotelsCategory',
            'hotelListings'
        ));
    }
}
