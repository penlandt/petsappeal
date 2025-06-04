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

    \DB::beginTransaction();

    try {
        $reservation = new \App\Models\Modules\Boarding\BoardingReservation();
        $reservation->location_id = $locationId;
        $reservation->client_id = $request->client_id;
        $reservation->boarding_unit_id = $request->boarding_unit_id;
        $reservation->check_in_date = $request->check_in_date;
        $reservation->check_out_date = $request->check_out_date;
        $reservation->price_total = $request->price_total;
        $reservation->notes = $request->notes ?? '';
        $reservation->save();

        $reservation->pets()->attach($request->pets);

        // Calculate tax
        $location = \App\Models\Location::findOrFail($locationId);
        $serviceTaxRate = floatval($location->service_tax_rate ?? 0);
        $priceTotal = floatval($reservation->price_total);
        $taxAmount = round($priceTotal * ($serviceTaxRate / 100), 2);
        $invoiceTotal = $priceTotal + $taxAmount;

        // Create Invoice
        $invoice = new \App\Models\Modules\Invoices\Invoice();
        $invoice->client_id = $request->client_id;
        $invoice->location_id = $locationId;
        $invoice->invoice_date = now()->toDateString();
        $invoice->due_date = now()->toDateString();
        $invoice->total_amount = $invoiceTotal;
        $invoice->amount_paid = 0;
        $invoice->status = 'Unpaid';
        $invoice->save();

        // Create InvoiceItem
        $description = 'Boarding (' .
            \Carbon\Carbon::parse($request->check_in_date)->format('m/d/Y') .
            ' - ' .
            \Carbon\Carbon::parse($request->check_out_date)->format('m/d/Y') .
            ')';

        $invoiceItem = new \App\Models\Modules\Invoices\InvoiceItem();
        $invoiceItem->invoice_id = $invoice->id;
        $invoiceItem->item_type = 'boarding';
        $invoiceItem->item_id = $reservation->id;
        $invoiceItem->description = $description;
        $invoiceItem->quantity = 1;
        $invoiceItem->unit_price = $priceTotal;
        $invoiceItem->total_price = $priceTotal;
        $invoiceItem->tax_amount = $taxAmount;
        $invoiceItem->save();

        \DB::commit();

        return redirect()->route('boarding.reservations.index')->with('success', 'Boarding reservation created successfully.');
    } catch (\Throwable $e) {
        \DB::rollBack();
        \Log::error('Failed to create boarding reservation and invoice: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while saving the boarding reservation.');
    }
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
        ->whereNotIn('status', ['Cancelled', 'No-Show'])
        ->get();

    $events = [];

    foreach ($reservations as $reservation) {
        $startDate = \Carbon\Carbon::parse($reservation->check_in_date);
        $endDate = \Carbon\Carbon::parse($reservation->check_out_date)->subDay(); // <== subtract 1 day

        $events[] = [
            'id' => $reservation->id,
            'resourceId' => $reservation->boarding_unit_id,
            'title' => $reservation->client
                ? $reservation->client->first_name . ' ' . $reservation->client->last_name
                : 'Unnamed Client',
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->addDay()->format('Y-m-d'), // FullCalendar expects exclusive end date
            'allDay' => true,
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
    \Log::info('Boarding status received', ['status' => $request->status]);

    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'boarding_unit_id' => 'required|exists:boarding_units,id',
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date|after:check_in_date',
        'pets' => 'required|array|min:1',
        'pets.*' => 'exists:pets,id',
        'price_total' => 'required|numeric',
        'notes' => 'nullable|string',
        'status' => 'required|in:Booked,Confirmed,Cancelled,No-Show,Checked In,Checked Out',
    ]);

    $user = auth()->user();
    $locationId = $user->selected_location_id;

    // Confirm reservation belongs to this location
    if ($reservation->location_id !== $locationId) {
        abort(403, 'Unauthorized');
    }

    // Track original dates
    $originalCheckIn = $reservation->check_in_date;
    $originalCheckOut = $reservation->check_out_date;

    // Update reservation
    $reservation->update([
        'client_id' => $request->client_id,
        'boarding_unit_id' => $request->boarding_unit_id,
        'check_in_date' => $request->check_in_date,
        'check_out_date' => $request->check_out_date,
        'price_total' => $request->price_total,
        'notes' => $request->notes ?? '',
        'status' => $request->status,
    ]);

    // Cancel invoice if reservation is cancelled
    if ($request->status === 'Cancelled') {
        $invoiceItem = \App\Models\Modules\Invoices\InvoiceItem::where('item_type', 'boarding')
            ->where('item_id', $reservation->id)
            ->first();

        if ($invoiceItem) {
            $invoice = $invoiceItem->invoice;

            if ($invoice && $invoice->status !== 'Paid') {
                $invoice->status = 'Voided';
                $invoice->total_amount = 0;
                $invoice->amount_paid = 0;
                $invoice->save();
            }
        }
    } else {
        // Recalculate invoice if dates changed
        if (
            $originalCheckIn !== $request->check_in_date ||
            $originalCheckOut !== $request->check_out_date
        ) {
            $invoiceItem = \App\Models\Modules\Invoices\InvoiceItem::where('item_type', 'boarding')
                ->where('item_id', $reservation->id)
                ->first();

            if ($invoiceItem) {
                $boardingUnit = $reservation->boardingUnit;
                $ratePerNight = $boardingUnit->price_per_night;

                $checkIn = \Carbon\Carbon::parse($request->check_in_date);
                $checkOut = \Carbon\Carbon::parse($request->check_out_date);
                $nights = $checkOut->diffInDays($checkIn);

                $newTotal = $nights * $ratePerNight;

                // Update invoice item
                $invoiceItem->quantity = $nights;
                $invoiceItem->unit_price = $ratePerNight;
                $invoiceItem->total_price = $newTotal;
                $invoiceItem->save();

                // Update total on the invoice
                $invoice = $invoiceItem->invoice;
                $invoice->total_amount = \App\Models\Modules\Invoices\InvoiceItem::where('invoice_id', $invoice->id)
                    ->sum(\DB::raw('total_price + tax_amount'));
                $invoice->save();
            }
        }
    }

    // Sync pets
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

public function cancel($id)
{
    try {
        \DB::beginTransaction();

        $reservation = \App\Models\Modules\Boarding\BoardingReservation::findOrFail($id);
        $reservation->status = 'Cancelled';
        $reservation->save();

        // Find invoice item linked to this reservation
        $invoiceItem = \App\Models\Modules\Invoices\InvoiceItem::where('item_type', 'boarding')
            ->where('item_id', $reservation->id)
            ->first();

        if ($invoiceItem) {
            $invoice = $invoiceItem->invoice;

            if ($invoice && $invoice->status !== 'Paid') {
                $invoice->status = 'Voided';
                $invoice->total_amount = 0;
                $invoice->amount_paid = 0;
                $invoice->save();
            }
        }

        \DB::commit();

        return redirect()->route('boarding.reservations.index')->with('success', 'Reservation cancelled and invoice voided.');
    } catch (\Throwable $e) {
        \DB::rollBack();
        \Log::error('Failed to cancel boarding reservation: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while cancelling the reservation.');
    }
}

}
