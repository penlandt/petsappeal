<?php

namespace App\Http\Controllers\Modules\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modules\Invoices\Invoice;

class InvoicePrintController extends Controller
{
    public function show(Invoice $invoice)
{
    return view('modules.invoices.invoice_print', [
        'invoice' => $invoice->load('client', 'items', 'location'),
    ]);
}

}
