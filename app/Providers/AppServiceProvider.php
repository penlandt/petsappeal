<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\PendingAppointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Existing logic for pending appointment count
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

        // Runtime SMTP injection for company email settings
        Mail::alwaysSending(function ($message) {
            if (!app()->environment('production')) {
                return; // Only enforce in production
            }

            $user = Auth::user();

            if (!$user) {
                // Unauthenticated: allow system-level tasks to use .env
                return;
            }

            if ($user->is_admin) {
                // Admins may use system mail config
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
