<?php

namespace App\Http\Controllers\Modules\Boarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modules\Boarding\BoardingUnit;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class BoardingUnitController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $boardingUnits = BoardingUnit::whereHas('location', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->with('location')->get();

        return view('modules.boarding.units.index', ['units' => $boardingUnits]);
    }

    public function create()
{
    $user = auth()->user();

    $locations = \App\Models\Location::where('company_id', $user->company_id)
        ->where('inactive', false)
        ->orderBy('name')
        ->get();

    return view('modules.boarding.units.create', compact('locations'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:kennel,cage,room,unit',
        'size' => 'required|in:small,medium,large,extra-large',
        'max_occupants' => 'required|integer|min:1',
        'price_per_night' => 'required|numeric|min:0',
        'location_id' => 'required|exists:locations,id',
    ]);

    \App\Models\Modules\Boarding\BoardingUnit::create($validated);

    return redirect()->route('boarding.units.index')->with('success', 'Boarding unit created successfully.');
}

public function edit($id)
{
    $user = auth()->user();

    $boardingUnit = \App\Models\Modules\Boarding\BoardingUnit::whereHas('location', function ($query) use ($user) {
        $query->where('company_id', $user->company_id);
    })->findOrFail($id);

    $locations = \App\Models\Location::where('company_id', $user->company_id)
        ->where('inactive', false)
        ->orderBy('name')
        ->get();

    return view('modules.boarding.units.edit', compact('boardingUnit', 'locations'));
}

public function update(Request $request, $id)
{
    $user = auth()->user();

    $boardingUnit = \App\Models\Modules\Boarding\BoardingUnit::whereHas('location', function ($query) use ($user) {
        $query->where('company_id', $user->company_id);
    })->findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:kennel,cage,room,unit',
        'size' => 'required|in:small,medium,large,extra-large',
        'max_occupants' => 'required|integer|min:1',
        'price_per_night' => 'required|numeric|min:0',
        'location_id' => 'required|exists:locations,id',
    ]);

    $boardingUnit->update($validated);

    return redirect()->route('boarding.units.index')->with('success', 'Boarding unit updated successfully.');
}

public function destroy($id)
{
    $user = auth()->user();

    $boardingUnit = \App\Models\Modules\Boarding\BoardingUnit::whereHas('location', function ($query) use ($user) {
        $query->where('company_id', $user->company_id);
    })->findOrFail($id);

    $boardingUnit->delete();

    return redirect()->route('boarding.units.index')->with('success', 'Boarding unit deleted successfully.');
}

}
