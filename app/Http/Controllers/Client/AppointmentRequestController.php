<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PendingAppointment;
use App\Models\Location;
use App\Models\Pet;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentRequestReceived;

class AppointmentRequestController extends Controller
{
    public function create()
{
    $clientUser = auth()->user();
    $client = $clientUser->client;

    $pets = $client->pets()->orderBy('name')->get();
    $services = \App\Models\Service::where('company_id', $client->company_id)->orderBy('name')->get();
    $locations = \App\Models\Location::where('company_id', $client->company_id)
        ->where('inactive', false)
        ->orderBy('name')
        ->get();

    return view('client.appointments.request', compact('pets', 'services', 'locations'));
}

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'pet_id'      => 'required|exists:pets,id',
            'service_id'  => 'required|exists:services,id',
            'date'        => 'required|date|after_or_equal:today',
            'time'        => 'required|date_format:H:i',
            'notes'       => 'nullable|string',
        ]);

        $pet = Pet::findOrFail($validated['pet_id']);

        // Verify ownership
        if ($pet->client->id !== auth()->user()->client_id) {
            abort(403, 'Unauthorized pet selection.');
        }

        $appointment = PendingAppointment::create($validated);

        // Notify the location via email, if email is configured
        $location = Location::find($validated['location_id']);
        if (!empty($location->email)) {
            Mail::to($location->email)->send(new AppointmentRequestReceived($appointment));
        }

        return redirect()->back()->with('success', 'Your appointment request has been submitted and is pending approval.');
    }
}
