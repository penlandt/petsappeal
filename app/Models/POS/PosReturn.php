<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosReturn extends Model
{
    use HasFactory;

    protected $table = 'pos_returns';

    protected $fillable = [
        'sale_id',
        'client_id',
        'product_id',
        'quantity',
        'price',
        'tax_amount',
        'refund_amount',
        'points_redeemed',
        'refund_method',
        'notes',
        'location_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }
}
