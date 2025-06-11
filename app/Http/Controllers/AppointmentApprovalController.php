<?php

namespace App\Http\Controllers;

use App\Models\PendingAppointment;
use App\Models\Staff;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentBooked;
use Carbon\Carbon;

class AppointmentApprovalController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $locationId = $user->selected_location_id;

    $appointments = PendingAppointment::with(['pet.client', 'service'])
        ->where('location_id', $locationId)
        ->where('status', 'Pending')  // Only pending requests
        ->orderBy('date')
        ->orderBy('time')
        ->get();

    return view('appointments.approval.index', compact('appointments'));
}



    public function edit(PendingAppointment $appointment)
    {
        $user = auth()->user();
        $staff = Staff::where('company_id', $user->company_id)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>', now());
            })
            ->orderBy('first_name')
            ->get();

        return view('appointments.approval.edit', compact('appointment', 'staff'));
    }

    public function update(Request $request, PendingAppointment $appointment)
{
    $request->validate([
        'staff_id' => 'required|exists:staff,id',
    ]);

    DB::beginTransaction();

    try {
        $pet = $appointment->pet;
        $client = $pet->client;

        $notes = trim($appointment->notes ?? '');
        $petNotes = trim($pet->notes ?? '');

        if ($petNotes) {
            $notes .= "\n\n---\n\nAdditional Notes from Pet Profile:\n" . $petNotes;
        }

        // Combine date and time into full datetime for start_time
        $location = $appointment->location;
        $start = Carbon::parse($appointment->date . ' ' . $appointment->time, $location->timezone ?? config('app.timezone'));

        $approved = Appointment::create([
            'location_id' => $appointment->location_id,
            'staff_id'    => $request->staff_id,
            'client_id'   => $client->id,
            'pet_id'      => $appointment->pet_id,
            'service_id'  => $appointment->service_id,
            'date'        => $appointment->date,
            'start_time'  => $start->format('Y-m-d H:i:s'),  // <--- fixed here
            'status'      => 'Booked',
            'notes'       => $notes,
            'price'       => $appointment->service->price ?? 0.00,
        ]);

        $appointment->delete();

        // Email confirmation
        $template = \App\Models\EmailTemplate::where('company_id', $appointment->location->company_id)
            ->where('type', 'grooming')
            ->where('template_key', 'appointment_booked')
            ->first();

        if ($template && !empty($client->email)) {
            $staff = \App\Models\Staff::find($request->staff_id);
            $service = $appointment->service;

            $replacements = [
                '{{ client_name }}' => $client->first_name . ' ' . $client->last_name,
                '{{ pet_name }}' => $pet->name,
                '{{ service_name }}' => $service->name,
                '{{ staff_name }}' => $staff->first_name . ' ' . $staff->last_name,
                '{{ appointment_date }}' => $start->format('F j, Y'),
                '{{ appointment_time }}' => $start->format('g:i A'),
                '{{ location_name }}' => $location->name ?? '',
                '{{ company_name }}' => $location->company->name ?? '',
            ];

            $html = strtr($template->body_html, $replacements);
            $plain = strtr($template->body_plain, $replacements);
            $subject = strtr($template->subject, $replacements);

            \App\Services\CompanyMailer::to($client->email)->send(
                new \App\Mail\GenericEmailTemplate($subject, $html, $plain)
            );
        }

        DB::commit();

        return redirect()->route('appointments.approval.index')->with('success', 'Appointment approved and moved to calendar.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to approve appointment: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to approve appointment. Please try again.');
    }
}


public function destroy(Request $request, PendingAppointment $appointment)
{
    $request->validate([
        'reason' => 'nullable|string|max:1000',
    ]);

    $reason = $request->input('reason', '');

    $appointment->status = 'Declined';
    $appointment->reason = $reason;  // save reason here
    $appointment->save();

    $client = $appointment->pet->client;

    if (!empty($client->email)) {
        \App\Services\CompanyMailer::to($client->email)->send(new \App\Mail\AppointmentDeclined($appointment));
    }

    return redirect()->route('appointments.approval.index')
        ->with('success', 'Appointment request declined and client notified.');
}


}
