<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandmarkSuggestion;
use App\Models\Attraction;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LandmarkSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $query = LandmarkSuggestion::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $suggestions = $query->latest()->paginate(15);

        return view('admin.suggestions.index', compact('suggestions'));
    }

    public function show(LandmarkSuggestion $suggestion)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.suggestions.show', compact('suggestion', 'categories'));
    }

    public function approve(Request $request, LandmarkSuggestion $suggestion)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $suggestion->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        ActivityLog::logActivity(
            'approve_suggestion',
            "Approved landmark suggestion: {$suggestion->name}",
            LandmarkSuggestion::class,
            $suggestion->id
        );

        return back()->with('success', 'Suggestion approved successfully.');
    }

    public function reject(Request $request, LandmarkSuggestion $suggestion)
    {
        $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $suggestion->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        ActivityLog::logActivity(
            'reject_suggestion',
            "Rejected landmark suggestion: {$suggestion->name}",
            LandmarkSuggestion::class,
            $suggestion->id
        );

        return back()->with('success', 'Suggestion rejected.');
    }

    public function convertToAttraction(LandmarkSuggestion $suggestion)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.suggestions.convert', compact('suggestion', 'categories'));
    }
}
