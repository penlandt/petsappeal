<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $company = $user?->company;

        // Deny access if no company is associated
        if (!$company) {
            abort(403, 'Access denied: no associated company.');
        }

        // Deny access if company is marked inactive
        if (!$company->active) {
            abort(403, 'Access denied: your company is inactive.');
        }

        // Allow billing pages unconditionally
        if ($request->is('billing') || $request->is('billing/*')) {
            return $next($request);
        }

        // Trial/subscription logic
        $isTrialExpired = $company->trial_ends_at && now()->greaterThan($company->trial_ends_at);
        $isSubscribed = $company->subscribed('default'); // Cashier

        if ($isTrialExpired && !$isSubscribed) {
            return redirect()->route('billing.plans')
                ->with('error', 'Your free trial has expired. Please choose a subscription plan to continue.');
        }

        return $next($request);
    }
}
