<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function formData()
    {
        $companyId = Auth::user()->company_id;

        $clients = Client::where('company_id', $companyId)
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'phone']);

        $services = Service::where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name', 'price']);

        return response()->json([
            'clients' => $clients,
            'services' => $services,
        ]);
    }

    public function searchClients(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $query = $request->input('q');

        $results = Client::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%$query%")
                  ->orWhere('last_name', 'like', "%$query%");
            })
            ->orderBy('last_name')
            ->limit(20)
            ->get()
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => "{$client->first_name} {$client->last_name}",
                ];
            });

        return response()->json($results);
    }

    public function getClientPets($clientId)
    {
        $user = Auth::user();

        $client = \App\Models\Client::where('company_id', $user->company_id)
            ->where('id', $clientId)
            ->firstOrFail();

        $pets = $client->pets()
            ->where('inactive', false)
            ->orderBy('name')
            ->get(['id', 'name', 'species', 'notes']);


        return response()->json($pets);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'location_id' => 'required|exists:locations,id',
        'staff_id' => 'required|exists:staff,id',
        'pet_id' => 'required|exists:pets,id',
        'service_id' => 'required|exists:services,id',
        'price' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
        'appointment_date' => 'required|date',
        'start_time' => 'required|date_format:H:i',
    ]);

    $start = \Carbon\Carbon::parse("{$validated['appointment_date']} {$validated['start_time']}");
    $service = \App\Models\Service::findOrFail($validated['service_id']);
    $end = $start->copy()->addMinutes($service->duration);

    $recurrenceType = $request->input('recurrence_type'); // 'weekly' or 'monthly'
    $recurrenceInterval = (int) $request->input('recurrence_interval'); // Number of weeks or months
    $recurrenceGroupId = $recurrenceType ? \Illuminate\Support\Str::uuid()->toString() : null;

    if ($recurrenceType && $recurrenceInterval > 0) {
        \App\Models\AppointmentRecurrenceRule::create([
            'recurrence_group_id' => $recurrenceGroupId,
            'location_id' => $validated['location_id'],
            'staff_id' => $validated['staff_id'],
            'pet_id' => $validated['pet_id'],
            'service_id' => $validated['service_id'],
            'price' => $validated['price'],
            'repeat_type' => $recurrenceType,
            'repeat_interval' => $recurrenceInterval,
            'start_date' => $start->toDateString(),
            'start_time' => $start->toTimeString(),
            'notes' => $validated['notes'] ?? null,
        ]);
    }

    $cutoffDate = now()->addMonths(6);
    $currentStart = $start->copy();
    $occurrence = 0;
    $skipped = [];

    while (true) {
        if ($occurrence > 0) {
            if ($recurrenceType === 'weekly') {
                $currentStart->addWeeks($recurrenceInterval);
            } elseif ($recurrenceType === 'monthly') {
                $currentStart->addMonths($recurrenceInterval);
            }

            if ($currentStart->greaterThan($cutoffDate)) {
                break;
            }
        }

        $dayOfWeek = strtolower($currentStart->format('l'));
        $startTimeStr = $currentStart->format('H:i:s');
        $dateStr = $currentStart->toDateString();

        $availability = \App\Models\StaffAvailability::where('staff_id', $validated['staff_id'])
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $startTimeStr)
            ->where('end_time', '>', $startTimeStr)
            ->first();

        $isException = \App\Models\StaffAvailabilityException::where('staff_id', $validated['staff_id'])
            ->whereDate('start_date', '<=', $dateStr)
            ->whereDate('end_date', '>=', $dateStr)
            ->exists();

        if (!$availability || $isException) {
            $skipped[] = $currentStart->format('M j, Y \a\t g:i A');
            $occurrence++;
            continue;
        }

        $appt = new \App\Models\Appointment();
        $appt->location_id = $validated['location_id'];
        $appt->staff_id = $validated['staff_id'];
        $appt->pet_id = $validated['pet_id'];
        $appt->service_id = $validated['service_id'];
        $appt->price = $validated['price'];
        $appt->notes = $validated['notes'] ?? null;
        $appt->start_time = $currentStart->copy();
        $appt->recurrence_group_id = $recurrenceGroupId;
        $appt->status = 'Booked';
        $appt->save();

        $occurrence++;

        if (!$recurrenceType || $recurrenceInterval < 1) {
            break;
        }
    }

    $message = 'Appointment(s) created!';
    if (count($skipped)) {
        $message .= ' The following dates were skipped due to staff unavailability:';
        foreach ($skipped as $s) {
            $message .= "\n- {$s}";
        }
    }

    return redirect()->route('schedule.index', [
        'date' => $request->input('date'),
        'location_id' => $request->input('location_id'),
    ])->with('success', $message);
}

    public function allAppointments()
    {
        $appointments = \App\Models\Appointment::with(['service', 'pet', 'staff', 'location'])
            ->where('status', '!=', 'Cancelled')
            ->get();

        $events = $appointments->map(function ($appointment) {
            $timezone = $appointment->location->timezone ?? config('app.timezone');
            $start = \Carbon\Carbon::parse($appointment->start_time);
            $end = \Carbon\Carbon::parse($appointment->start_time)->addMinutes($appointment->service->duration ?? 60);

            return [
                'id' => $appointment->appointment_id,
                'title' => $appointment->pet->name ?? 'Appointment',
                'start' => $start->format('Y-m-d\TH:i:s'),
                'end' => $end->format('Y-m-d\TH:i:s'),
                'resourceId' => $appointment->staff_id,
                'status' => $appointment->status,
                'price' => $appointment->price,
                'service_name' => $appointment->service->name ?? null,
                'client_name' => optional($appointment->pet->client)->first_name . ' ' . optional($appointment->pet->client)->last_name,
                'client_phone' => optional($appointment->pet->client)->phone,
                'notes' => $appointment->notes,
            ];
            
        });

        return response()->json($events);
    }

    public function show($id)
    {
        try {
            $appointment = Appointment::with([
                'pet.client',
                'service',
                'location',
                'staff',
            ])->findOrFail($id);

            return response()->json([
                'appointment' => $appointment,
                'client' => $appointment->pet->client ?? null,
                'pet' => $appointment->pet,
                'service' => $appointment->service,
                'location' => $appointment->location,
                'staff' => $appointment->staff,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Error loading appointment: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Failed to load appointment.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        \Log::info('Received update payload:', $request->all());

        $applyToSeries = $request->input('apply_to_series'); // 'single' or 'future'

        try {
            $validated = $request->validate([
                'pet_id' => 'required|exists:pets,id',
                'service_id' => 'required|exists:services,id',
                'price' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
                'status' => 'required|in:Booked,Confirmed,Cancelled,No-Show,Checked In,Checked Out',

                'appointment_date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'staff_id' => 'required|exists:staff,id',
            ]);

            $appointment = Appointment::findOrFail($id);

            $start = \Carbon\Carbon::parse("{$validated['appointment_date']} {$validated['start_time']}");
            $appointment->start_time = $start;
            $appointment->staff_id = $validated['staff_id'];

            unset($validated['appointment_date'], $validated['start_time']);

            if ($applyToSeries === 'future' && $appointment->recurrence_group_id) {
                Appointment::where('recurrence_group_id', $appointment->recurrence_group_id)
                    ->where('start_time', '>=', $appointment->start_time)
                    ->update($validated);
            } else {
                $appointment->update($validated);
            }

            return response()->json(['message' => 'Appointment updated successfully']);
        } catch (\Throwable $e) {
            \Log::error('Failed to update appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update appointment.'], 500);
        }
    }
}
