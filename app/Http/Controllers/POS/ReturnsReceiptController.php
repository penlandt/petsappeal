<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\POS\ReturnModel;
use Illuminate\Http\Request;
use App\Models\POS\PosReturn;


class ReturnsReceiptController extends Controller
{
    public function show(PosReturn $return)

    {
        // Eager load sale, client, and location relationships
        $return->load([
            'client',
            'location',
            'product',
            'sale.client',
            'sale.location',
        ]);

        return view('pos.return-receipt', [
            'return' => $return,
        ]);
    }
}
