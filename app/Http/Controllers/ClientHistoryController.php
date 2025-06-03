<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Modules\Invoices\Invoice;
use App\Models\POS\Sale;
use Illuminate\Support\Collection;

class ClientHistoryController extends Controller
{
    public function show(Client $client)
    {
        $invoices = $client->invoices()
    ->where('status', '!=', 'Voided')  // ← This line filters them out
    ->with('location')
    ->get()
    ->map(function ($invoice) {
        $invoice->type = 'invoice';
        return $invoice;
    });

        $sales = Sale::where('client_id', $client->id)->with('location')->get()->map(function ($sale) {
            $sale->type = 'sale';
            return $sale;
        });

        $history = $invoices
            ->merge($sales)
            ->sortByDesc(function ($record) {
                return [$record->created_at, $record->id];
            })
            ->values();

        return view('clients.history', compact('client', 'history'));
    }
}
