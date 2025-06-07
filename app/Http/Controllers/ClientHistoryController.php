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

    $returns = \App\Models\POS\ReturnTransaction::with(['items.product', 'location'])
        ->where('client_id', $client->id)
        ->get()
        ->map(function ($return) {
            $return->type = 'return';

            // Find associated restored points from loyalty_point_transactions
            $restored = DB::table('loyalty_point_transactions')
                ->where('client_id', $return->client_id)
                ->where('type', 'earn')
                ->where('description', 'like', '%Return reversal of redeemed points%')
                ->where('created_at', $return->created_at)
                ->sum('points');

                $return->points_restored = $restored;
                $return->amount = $return->refund_amount * -1;

            return $return;
        });

    $history = collect($invoices)
        ->merge($sales)
        ->merge($returns)
        ->sortByDesc(function ($record) {
            return [$record->created_at, $record->id];
        })
        ->values();

    return view('clients.history', compact('client', 'history'));
}

}
