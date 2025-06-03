<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Modules\Invoices\Invoice;
use App\Models\POS\Sale;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClientHistoryController extends Controller
{
    public function show(Client $client)
    {
        $invoices = $client->invoices()
            ->where('status', '!=', 'Voided')
            ->with('location')
            ->get()
            ->map(function ($invoice) {
                $invoice->type = 'invoice';
                return $invoice;
            });

        $sales = Sale::where('client_id', $client->id)
            ->with('location')
            ->get()
            ->map(function ($sale) {
                $sale->type = 'sale';
                return $sale;
            });

        $returns = DB::table('pos_returns')
            ->where('client_id', $client->id)
            ->join('products', 'products.id', '=', 'pos_returns.product_id')
            ->join('locations', 'locations.id', '=', 'pos_returns.location_id')
            ->select('pos_returns.*', 'products.name as product_name', 'locations.name as location_name')
            ->get()
            ->map(function ($return) {
                $return->type = 'return';
                return $return;
            });

        $history = collect($invoices)
            ->merge(collect($sales))
            ->merge($returns)
            ->sortByDesc(function ($record) {
                return [$record->created_at, $record->id];
            })
            ->values();

        return view('clients.history', compact('client', 'history'));
    }
}
