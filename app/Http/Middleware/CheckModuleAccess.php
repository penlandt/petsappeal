<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = auth()->user();

        if (!$user || !$user->company || !$user->company->hasModuleAccess($module)) {
            return redirect()->route('dashboard')->with('error', 'You do not have access to this module.');
        }

        return $next($request);
    }
}
