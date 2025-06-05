<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $locationId = $user->selected_location_id;

        $upcomingAppointments = \App\Models\Appointment::with(['pet.client', 'service', 'staff'])
            ->where('location_id', $locationId)
            ->whereDate('start_time', '>=', Carbon::today())
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        $upcomingReservations = \App\Models\Modules\Boarding\BoardingReservation::with(['client', 'pets'])
            ->where('location_id', $locationId)
            ->whereDate('check_in_date', '>=', Carbon::today())
            ->orderBy('check_in_date')
            ->limit(10)
            ->get();

        return view('dashboard', [
            'upcomingAppointments' => $upcomingAppointments,
            'upcomingReservations' => $upcomingReservations,
        ]);
    }

}
