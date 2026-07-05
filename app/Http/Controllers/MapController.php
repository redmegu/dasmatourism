<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Attraction;
use App\Models\MapMarker;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $markers = MapMarker::where('is_visible', true)
            ->with(['attraction' => function ($query) {
                $query->approved()->with(['category', 'primaryImage']);
            }])
            ->get();

        if (auth()->check()) {
            // map view is not logged
        }

        return view('map.index', compact('markers'));
    }

    public function getMarkers()
    {
        $markers = MapMarker::where('is_visible', true)
            ->with(['attraction' => function ($query) {
                $query->approved()->select('id', 'name', 'slug', 'category_id', 'is_historical_site')->with('category:id,name,icon');
            }])
            ->get()
            ->filter(function ($marker) {
                // Only include markers that have an associated attraction
                return $marker->attraction !== null;
            })
            ->map(function ($marker) {
                return [
                    'id' => $marker->id,
                    'lat' => (float) $marker->latitude,
                    'lng' => (float) $marker->longitude,
                    'icon' => $marker->marker_icon,
                    'color' => $marker->marker_color,
                    'attraction' => [
                        'id' => $marker->attraction->id,
                        'name' => $marker->attraction->name,
                        'slug' => $marker->attraction->slug,
                        'category' => $marker->attraction->category->name,
                        'category_id' => $marker->attraction->category->id,
                        'category_icon' => $marker->attraction->category->icon,
                        'is_historical' => $marker->attraction->is_historical_site,
                        'url' => route('attractions.show', $marker->attraction->slug),
                    ]
                ];
            })
            ->values(); // Re-index the array after filtering

        return response()->json($markers);
    }
}
