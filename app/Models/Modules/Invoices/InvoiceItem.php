<?php

namespace App\Models\Modules\Invoices;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'item_id',
        'item_type',
        'description',
        'quantity',
        'unit_price',
        'total_price',     // or 'total_price', if you renamed it
        'tax_amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
