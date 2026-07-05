<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Actions that are always shown (admin operations).
     */
    const ADMIN_ACTIONS = ['create', 'update', 'delete', 'approve', 'reject', 'publish', 'unpublish'];

    /**
     * Actions that are shown only for non-admin users.
     */
    const USER_ACTIONS = ['register', 'review', 'update'];

    /**
     * Model types that are considered "user-facing important" events.
     * Used to narrow down which 'update' logs from regular users are kept.
     */
    const USER_MODEL_TYPES = [
        'App\\Models\\User',
        'App\\Models\\Attraction',
        'App\\Models\\Business',
    ];

    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // ── Base scope: only include meaningful logs ──────────────────────
        // Wrapped in a single where-group so that any additional user filters
        // are AND-ed against this scope rather than OR-ing around it.
        $query->where(function ($base) {
            // Admin / business-owner actions on any model
            $base->where(function ($q) {
                $q->whereIn('user_type', ['administrator', 'business_owner'])
                  ->whereIn('action', self::ADMIN_ACTIONS);
            })
            // Regular user: new account registrations
            ->orWhere('action', 'register')
            // Regular user: posting a review
            ->orWhere('action', 'review')
            // Regular user: profile/info update (logged explicitly)
            ->orWhere(function ($q) {
                $q->where('user_type', 'user')
                  ->where('action', 'update')
                  ->whereIn('model_type', self::USER_MODEL_TYPES);
            });
        });
        // ─────────────────────────────────────────────────────────────────

        // ── Optional filters from the UI ──────────────────────────────────
        if ($request->filled('user_type') && $request->user_type !== 'all') {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        // model_type is stored as the full class name in the DB (e.g. App\Models\Attraction).
        // The dropdown sends the full class name so we can do an exact match instead of LIKE.
        if ($request->filled('model_type') && $request->model_type !== 'all') {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        // ─────────────────────────────────────────────────────────────────

        $logs = $query->paginate(20)->withQueryString();

        // ── Filter options derived from what's actually in the DB ─────────
        $userTypes = ActivityLog::distinct()->pluck('user_type')->filter()->sort()->values();

        // Actions: merge hardcoded list with whatever is actually in the DB
        $actions = ActivityLog::distinct()
            ->pluck('action')
            ->merge(self::ADMIN_ACTIONS)
            ->merge(self::USER_ACTIONS)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // model_type: keep the full class name as the option value so the
        // filter can do an exact match, and derive the label via class_basename().
        $modelTypes = ActivityLog::distinct()
            ->pluck('model_type')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->mapWithKeys(fn ($type) => [$type => class_basename($type)]);
        // ─────────────────────────────────────────────────────────────────

        return view('admin.activity-logs.index', compact('logs', 'userTypes', 'actions', 'modelTypes'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();
        return back()->with('success', 'Activity log deleted successfully.');
    }

    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $date  = now()->subDays($request->days);
        $count = ActivityLog::where('created_at', '<', $date)->delete();

        return back()->with('success', "Deleted {$count} logs older than {$request->days} days.");
    }
}
