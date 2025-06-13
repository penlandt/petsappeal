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

    if (!$user || !$user->company || !$user->company->hasModule($module)) {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Access denied. Module not enabled.'], 403);
        }

        abort(403, 'You do not have access to this module. Please upgrade to gain access');
    }

    return $next($request);
}

}
