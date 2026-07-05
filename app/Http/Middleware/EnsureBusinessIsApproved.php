<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureBusinessIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !$user->business || $user->business->status !== 'approved') {
            return redirect()
                ->route('business-owner.dashboard')
                ->with('error', 'Your business must be approved before accessing promotions.');
        }

        return $next($request);
    }
}
