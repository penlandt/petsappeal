<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Modules\Invoices\Invoice;
use App\Models\Modules\Invoices\InvoiceItem;
use Carbon\Carbon;

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
        \Log::info('Appointment request payload:', $request->all());

        
        $validated = $request->validate([
            'client_id'     => 'required|exists:clients,id',
            'pet_id'        => 'required|exists:pets,id',
            'service_id'    => 'required|exists:services,id',
            'staff_id'      => 'required|exists:staff,id',
            'start_time'    => 'required|date_format:H:i',
            'appointment_date' => 'required|date',
            'notes'         => 'nullable|string',
        ]);
    
        $user = auth()->user();
        $locationId = $user->selected_location_id;
    
        $service = Service::findOrFail($validated['service_id']);
    
        // Combine date and time into full datetime
        $start = Carbon::parse($validated['appointment_date'] . ' ' . $validated['start_time'], $user->time_zone ?? config('app.timezone'));

        $end = (clone $start)->addMinutes($service->duration ?? 30);
    
        // Create the appointment
        $appt = Appointment::create([
            'company_id'    => $user->company_id,
            'location_id'   => $locationId,
            'client_id'     => $validated['client_id'],
            'pet_id'        => $validated['pet_id'],
            'service_id'    => $validated['service_id'],
            'staff_id'      => $validated['staff_id'],
            'start'         => $start,
            'end'           => $end,
            'start_time'    => $start,
            'notes'         => $validated['notes'],
            'status'        => 'Booked',
            'price'         => $service->price,
        ]);
        
        $appt->refresh(); // ✅ This guarantees the ID is loaded
        
        \Log::info('Created appointment object:', $appt->toArray());

    
        // Create invoice
        $user = auth()->user();
        $location = \App\Models\Location::find($user->selected_location_id);
        $taxRate = $location?->service_tax_rate ?? 0;

        // Step 1: Create the invoice with placeholder total
        $invoice = Invoice::create([
            'location_id'    => $locationId,
            'client_id'      => $validated['client_id'],
            'invoice_date'   => now()->toDateString(),
            'total_amount'   => 0,
            'amount_paid'    => 0,
            'status'         => 'Unpaid',
        ]);

        // Step 2: Calculate and save invoice item
        $unit_price = $service->price;
        $quantity = 1;
        $total_price = $unit_price * $quantity;
        $tax_amount = $total_price * ($taxRate / 100);

        \Log::info('Invoice item values', [
            'invoice_id'   => $invoice->id,
            'item_id'      => $appt->appointment_id,
            'unit_price'   => $unit_price,
            'quantity'     => $quantity,
            'total_price'  => $total_price,
            'tax_amount'   => $tax_amount,
        ]);

        InvoiceItem::create([
            'invoice_id'     => $invoice->id,
            'item_id'        => $appt->appointment_id,
            'item_type'      => 'appointment',
            'description'    => $service->name,
            'quantity'       => $quantity,
            'unit_price'     => $unit_price,
            'total_price'    => $total_price,
            'tax_amount'     => $tax_amount,
        ]);

        // Step 3: Recalculate invoice total from all related items
        $invoiceTotal = \App\Models\Modules\Invoices\InvoiceItem::where('invoice_id', $invoice->id)
            ->sum(\DB::raw('total_price + tax_amount'));

        \DB::table('invoices')
            ->where('id', $invoice->id)
            ->update(['total_amount' => (float) $invoiceTotal]);
                        
        return redirect()->route('schedule.index')->with('success', 'Appointment saved successfully.');
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

        // 💥 If status is Cancelled, cancel related invoice
        if ($validated['status'] === 'Cancelled') {
            $invoiceItem = InvoiceItem::where('appointment_id', $appointment->id)->first();
            if ($invoiceItem && $invoiceItem->invoice) {
                $invoice = $invoiceItem->invoice;
                $invoice->status = 'Voided';
                $invoice->save();
            }
        }


        return response()->json(['message' => 'Appointment updated successfully']);
    } catch (\Throwable $e) {
        \Log::error('Failed to update appointment: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to update appointment.'], 500);
    }
}

}