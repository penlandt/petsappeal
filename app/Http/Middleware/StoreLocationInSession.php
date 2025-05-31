<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreLocationInSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('location_id')) {
            session(['selected_location_id' => $request->input('location_id')]);
        }

        return $next($request);
    }
}
