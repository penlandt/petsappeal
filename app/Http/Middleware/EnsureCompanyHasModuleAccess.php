<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCompanyHasModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $moduleName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $moduleName)
    {
        $user = Auth::user();

        if (!$user || !$user->company || !$user->company->modules->contains('module_name', $moduleName)) {
            abort(403, 'Access denied: your company does not have access to this module.');
        }

        return $next($request);
    }
}
