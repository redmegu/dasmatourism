<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AnalyticsLog;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests (page views)
        if ($request->isMethod('GET') && !$request->ajax()) {
            $this->logPageView($request);
        }

        return $response;
    }

    protected function logPageView(Request $request)
    {
        // Skip admin, API, and asset routes
        $path = $request->path();
        if (
            str_starts_with($path, 'admin') ||
            str_starts_with($path, 'api') ||
            str_starts_with($path, 'storage') ||
            str_starts_with($path, 'css') ||
            str_starts_with($path, 'js')
        ) {
            return;
        }

        try {
            AnalyticsLog::create([
                'event_type' => 'page_view',
                'page' => $request->path(),
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => json_encode([
                    'url' => $request->fullUrl(),
                    'referer' => $request->header('referer'),
                    'method' => $request->method(),
                ]),
            ]);
        } catch (\Exception $e) {
            // Silently fail to not break the application
            \Log::error('Analytics logging failed: ' . $e->getMessage());
        }
    }
}
