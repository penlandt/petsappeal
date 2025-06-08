<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\POS\Sale;

class ReportsController extends Controller
{
    public function endOfDay()
    {
        $user = auth()->user();
        $location = \App\Models\Location::findOrFail($user->selected_location_id);
        $timezone = $location->timezone ?? config('app.timezone');

        $startOfDay = now()->setTimezone($timezone)->startOfDay()->timezone('UTC');
        $endOfDay = now()->setTimezone($timezone)->endOfDay()->timezone('UTC');

        $sales = Sale::with([
            'client',
            'items',
            'loyaltyPointTransactions',
            'payments',
        ])
        ->where('location_id', $location->id)
        ->whereBetween('created_at', [$startOfDay, $endOfDay])
        ->orderBy('created_at', 'asc')
        ->get();

        return view('pos.reports.end-of-day', [
            'sales' => $sales,
            'location' => $location,
            'timezone' => $timezone,
        ]);
    }
}
