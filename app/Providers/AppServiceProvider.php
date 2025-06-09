<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\PendingAppointment;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user = Auth::user();

            $pendingRequestsCount = 0;
            if ($user && $user->selected_location_id) {
                $pendingRequestsCount = PendingAppointment::where('location_id', $user->selected_location_id)
                    ->where('status', 'Pending')
                    ->count();
            }

            $view->with('pendingRequestsCount', $pendingRequestsCount);
        });
    }
}
