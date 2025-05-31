<?php

namespace App\Http\Controllers\Modules\Boarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modules\Boarding\BoardingReservation;
use App\Models\Modules\Boarding\BoardingUnit;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\Pet;

class BoardingReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $locationId = $user->selected_location_id;

        $boardingUnits = BoardingUnit::where('location_id', $locationId)
            ->orderBy('name')
            ->get();

        $reservations = BoardingReservation::with(['boardingUnit', 'client'])
            ->whereHas('boardingUnit', function ($query) use ($locationId) {
                $query->where('location_id', $locationId);
            })
            ->orderBy('check_in_date')
            ->get();

        return view('modules.boarding.reservations.index', compact('reservations', 'boardingUnits'));
    }

    public function create()
{
    $user = auth()->user();
    $locationId = $user->selected_location_id;

    $boardingUnits = BoardingUnit::where('location_id', $locationId)
        ->orderBy('name')
        ->get();

    $clients = Client::where('company_id', $user->company_id)
        ->orderBy('last_name')
        ->get();

    return view('modules.boarding.reservations.create', compact('boardingUnits', 'clients', 'locationId'));
}


public function store(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'boarding_unit_id' => 'required|exists:boarding_units,id',
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date|after:check_in_date',
        'pets' => 'required|array|min:1',
        'pets.*' => 'exists:pets,id',
        'price_total' => 'required|numeric',
        'notes' => 'nullable|string',
    ]);

    $user = auth()->user();
    $locationId = $user->selected_location_id;

    $reservation = new \App\Models\Modules\Boarding\BoardingReservation();
    $reservation->location_id = $locationId;
    $reservation->client_id = $request->client_id;
    $reservation->boarding_unit_id = $request->boarding_unit_id;
    $reservation->check_in_date = $request->check_in_date;
    $reservation->check_out_date = $request->check_out_date;
    $reservation->price_total = $request->price_total;
    $reservation->notes = $request->notes ?? '';
    $reservation->save();

    // Attach pets to the reservation
    $reservation->pets()->attach($request->pets);

    return redirect()->route('boarding.reservations.index')->with('success', 'Boarding reservation created successfully.');
}

    public function getClientPets($clientId)
    {
        $user = auth()->user();

        $pets = Pet::where('client_id', $clientId)
            ->where('company_id', $user->company_id)
            ->where('inactive', false)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($pets);
    }

    public function getPetNotes(Request $request)
    {
        $petIds = $request->input('pet_ids', []);

        $pets = Pet::whereIn('id', $petIds)->get(['id', 'name', 'notes']);

        $formattedNotes = [];

        foreach ($pets as $pet) {
            if (!empty($pet->notes)) {
                $formattedNotes[] = "{$pet->name}: {$pet->notes}";
            }
        }

        return response()->json([
            'notes' => implode("\n", $formattedNotes),
        ]);
    }

    public function json()
{
    $user = auth()->user();
    $locationId = $user->selected_location_id;

    $reservations = \App\Models\Modules\Boarding\BoardingReservation::with(['client', 'boardingUnit'])
        ->whereHas('boardingUnit', function ($query) use ($locationId) {
            $query->where('location_id', $locationId);
        })
        ->get();

    $events = [];

    foreach ($reservations as $reservation) {
        $location = $reservation->location;
    
        $checkInTime = $location?->check_in_time ?? '13:00:00';   // fallback to 1pm
        $checkOutTime = $location?->check_out_time ?? '11:00:00'; // fallback to 11am
    
        $events[] = [
            'id' => $reservation->id,
            'resourceId' => $reservation->boarding_unit_id,
            'title' => $reservation->client
                ? $reservation->client->first_name . ' ' . $reservation->client->last_name
                : 'Unnamed Client',
            'start' => $reservation->check_in_date . 'T' . $checkInTime,
            'end' => $reservation->check_out_date . 'T' . $checkOutTime,
        ];
    }
    

    return response()->json($events);
}

public function edit($id)
{
    $user = auth()->user();
    $locationId = $user->selected_location_id;

    $reservation = BoardingReservation::with('pets')->findOrFail($id);

    if ($reservation->location_id !== $locationId) {
        abort(403, 'Unauthorized');
    }

    $clients = Client::where('company_id', $user->company_id)->orderBy('last_name')->get();
    $boardingUnits = BoardingUnit::where('location_id', $locationId)->orderBy('name')->get();
    $petIds = $reservation->pets->pluck('id')->toArray();

    $reservation->check_in_date = \Carbon\Carbon::parse($reservation->check_in_date);
    $reservation->check_out_date = \Carbon\Carbon::parse($reservation->check_out_date);

    return view('modules.boarding.reservations.edit', compact('reservation', 'clients', 'boardingUnits', 'petIds'));

}


public function update(Request $request, BoardingReservation $reservation)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'boarding_unit_id' => 'required|exists:boarding_units,id',
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date|after:check_in_date',
        'pets' => 'required|array|min:1',
        'pets.*' => 'exists:pets,id',
        'price_total' => 'required|numeric',
        'notes' => 'nullable|string',
    ]);

    $user = auth()->user();
    $locationId = $user->selected_location_id;

    // Confirm reservation belongs to this location
    if ($reservation->location_id !== $locationId) {
        abort(403, 'Unauthorized');
    }

    $reservation->update([
        'client_id' => $request->client_id,
        'boarding_unit_id' => $request->boarding_unit_id,
        'check_in_date' => $request->check_in_date,
        'check_out_date' => $request->check_out_date,
        'price_total' => $request->price_total,
        'notes' => $request->notes ?? '',
    ]);

    $reservation->pets()->sync($request->pets);

    return redirect()->route('boarding.reservations.index')->with('success', 'Reservation updated successfully.');
}
    
public function destroy(BoardingReservation $reservation)
{
    $userLocationId = auth()->user()->selected_location_id;

    // Prevent deleting reservations from a different location
    if ($reservation->location_id !== $userLocationId) {
        abort(403, 'Unauthorized');
    }

    $reservation->delete();

    return redirect()->route('boarding.reservations.index')
        ->with('success', 'Boarding reservation deleted successfully.');
}

}
