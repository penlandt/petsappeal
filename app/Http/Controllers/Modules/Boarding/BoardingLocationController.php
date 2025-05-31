<?php

namespace App\Http\Controllers\Modules\Boarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class BoardingLocationController extends Controller
{
    public function selectLocation(Request $request)
    {
        $user = $request->user();
        $companyId = $user->company_id;

        // If location is already set, redirect to boarding calendar index
        if (session('boarding_location_id')) {
            return redirect()->route('boarding.reservations.index');
        }

        $locations = Location::where('company_id', $companyId)
            ->where('inactive', false)
            ->orderBy('name')
            ->get();

        return view('modules.boarding.location-select', [
            'locations' => $locations,
        ]);
    }

    public function setLocation(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
        ]);
    
        session(['boarding_location_id' => $request->location_id]);
    
        return redirect()->route('boarding.reservations.index');
    }

}
