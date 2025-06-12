<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOnboardingIsComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Allow access to onboarding, logout, and billing routes
        if (
            $request->routeIs('onboarding.*') ||
            $request->routeIs('logout') ||
            $request->routeIs('billing.*')
        ) {
            return $next($request);
        }

        $company = $user->company;

        if (!$company) {
            return redirect()->route('onboarding.step.company');
        }

        // ✅ If onboarding is already marked complete, allow access
        if ($company->onboarding_complete) {
            return $next($request);
        }

        // 🧭 Otherwise, proceed step-by-step through the wizard
        if ($company->locations()->count() === 0) {
            return redirect()->route('onboarding.step.location');
        }

        if ($company->staff()->count() === 0) {
            return redirect()->route('onboarding.step.staff');
        }

        if ($company->services()->count() === 0) {
            return redirect()->route('onboarding.step.service');
        }

        return $next($request);
    }
}
