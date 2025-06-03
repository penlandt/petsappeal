<?php

namespace App\Models\Modules\Invoices;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'client_id',
        'status',
        'notes',
        'subtotal',
        'tax',
        'total',
        'invoice_date',
        'due_date',
        'paid_at',
    ];

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
