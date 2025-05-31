<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSelectedLocation
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('selected_location_id')) {
            return redirect()->route('boarding.select-location')->with('error', 'Please select a location before continuing.');
        }

        return $next($request);
    }
}
