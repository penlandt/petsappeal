<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class ForceClientPasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
{
    $user = auth('client')->user();

    \Log::info('🔐 ForceClientPasswordChange middleware hit', [
        'user_id' => optional($user)->id,
        'must_change_password' => optional($user)->must_change_password,
        'current_route' => \Illuminate\Support\Facades\Route::currentRouteName(),
    ]);

    if ($user && $user->must_change_password) {
        $allowedRoutes = [
            'client.password.change',
            'client.password.update',
            'client.logout',
        ];

        if (!in_array(Route::currentRouteName(), $allowedRoutes)) {
            \Log::info('🔁 Redirecting to password change screen');
            return redirect()->route('client.password.change');
        }
    }

    return $next($request);
}

}
