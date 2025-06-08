<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Location;

class SetDefaultLocationOnLogin
{
    public function handle(Login $event)
{
    $user = $event->user;

    // Only apply to App\Models\User, not ClientUser
    if (!($user instanceof \App\Models\User)) {
        return;
    }

    if (!$user->selected_location_id) {
        $defaultLocation = \App\Models\Location::where('company_id', $user->company_id)
            ->where('inactive', false)
            ->orderBy('name')
            ->first();

        if ($defaultLocation) {
            $user->selected_location_id = $defaultLocation->id;
            $user->save();
            \Log::info("Default location set for user {$user->id}: {$defaultLocation->id}");
        } else {
            \Log::warning("No active location found for user {$user->id}");
        }
    }
}

}
