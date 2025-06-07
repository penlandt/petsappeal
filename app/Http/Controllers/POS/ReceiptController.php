<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\POS\Sale;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function show(Sale $sale)
    {
        return view('pos.receipt', [
            'sale' => $sale->load('location.company.loyaltyProgram', 'client'),
        ]);
    }
}
