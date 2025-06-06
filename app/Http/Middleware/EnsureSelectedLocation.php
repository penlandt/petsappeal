<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;

class EnsureSelectedLocation
{
    public function handle(Request $request, Closure $next)
    {

        \Log::info('EnsureSelectedLocation middleware hit', [
            'user_id' => optional(Auth::user())->id,
            'selected_location_id' => optional(Auth::user())->selected_location_id,
            'url' => $request->path(),
        ]);
        
        $user = Auth::user();

        if ($user) {
            // Get the user's company's active locations
            $activeLocations = Location::where('company_id', $user->company_id)
                ->where('inactive', false)
                ->pluck('id')
                ->toArray();

            // If user has no selected location, or it’s no longer valid, or multiple locations are available
            if (
                is_null($user->selected_location_id) ||
                !in_array($user->selected_location_id, $activeLocations)
            ) {
                if (!($request->is('select-location') && $request->isMethod('get')) && !$request->is('logout')) {
                    return redirect()->route('select-location')->with('error', 'Please select your location.');
                }
            }
        }

        return $next($request);
    }
}
