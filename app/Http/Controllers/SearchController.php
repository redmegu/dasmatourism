<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Business;
use App\Models\AnalyticsLog;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->route('home');
        }

        // Log search
        AnalyticsLog::create([
            'user_id' => auth()->id(),
            'event_type' => 'search',
            'metadata' => json_encode(['query' => $query]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Search attractions
        $attractions = Attraction::approved()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('address', 'like', "%{$query}%");
            })
            ->with(['category', 'primaryImage'])
            ->limit(10)
            ->get();

        // Search businesses
        $businesses = Business::approved()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('address', 'like', "%{$query}%");
            })
            ->with(['category', 'primaryImage'])
            ->limit(10)
            ->get();

        return view('search', compact('query', 'attractions', 'businesses'));
    }
}
