<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Blade;
use App\Models\PendingAppointment;
use App\Models\EmailSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ✅ Register custom Blade component for client guest layout
        Blade::component('layouts.client-guest', 'client-guest-layout');

        // Existing logic: pending appointment counter
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

        // Runtime SMTP config override (manual injection)
        app()->resolving(\Illuminate\Mail\Mailer::class, function ($mailer, $app) {
            if (!app()->environment('production')) {
                return;
            }

            $user = Auth::user();

            if (!$user || $user->is_admin) {
                // System or admin email: use .env mail settings
                return;
            }

            if (!$user->company_id) {
                throw new \Exception('Cannot send email: No company context and not an admin.');
            }

            $emailSettings = EmailSetting::where('company_id', $user->company_id)->first();

            if (!$emailSettings) {
                throw new \Exception('Email settings not found for your company.');
            }

            $emailSettings->applyAsMailConfig();
        });
    }
}
