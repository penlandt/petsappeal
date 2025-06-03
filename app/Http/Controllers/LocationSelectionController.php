<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;

class LocationSelectionController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $companyId = $user->company_id;

        // Only show active locations for the user's company
        $locations = Location::where('company_id', $companyId)
            ->where('inactive', false)
            ->get();

        return view('select-location', compact('locations'));
    }

    public function store(Request $request)
{
    $request->validate([
        'location_id' => 'required|exists:locations,id',
    ]);

    $user = Auth::user();

    $location = Location::where('id', $request->location_id)
        ->where('company_id', $user->company_id)
        ->where('inactive', false)
        ->firstOrFail();

    $user->selected_location_id = $location->id;
    $user->save();

    // ✅ Reload updated user into auth system
    Auth::setUser($user->fresh());

    \Log::info('Selected location saved:', [
        'user_id' => $user->id,
        'selected_location_id' => $user->selected_location_id,
    ]);

    return redirect()->intended('/dashboard')->with('success', 'Location selected successfully.');
}


}
