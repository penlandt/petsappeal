<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasCompany
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If user is logged in and doesn't have a company, redirect to company creation
        if ($user && !$user->company_id && !$request->routeIs('companies.create') && !$request->routeIs('companies.store')) {
            return redirect()->route('companies.create');
        }

        return $next($request);
    }
}
