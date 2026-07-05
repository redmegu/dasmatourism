<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogAuthActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Log login if user just authenticated
        if (Auth::check() && !session()->has('logged_auth_activity')) {
            ActivityLog::logActivity(
                'login',
                Auth::user()->name . ' logged in',
                'App\Models\User',
                Auth::id()
            );
            session(['logged_auth_activity' => true]);
        }

        return $next($request);
    }
}
