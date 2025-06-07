<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnTransaction extends Model
{
    use HasFactory;

    protected $table = 'pos_returns';

    protected $fillable = [
        'sale_id',
        'client_id',
        'location_id',
        'refund_method',
        'refund_amount',
        'points_redeemed',
        'product_id', // optional: only used if needed
    ];

    public function items()
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id');
    }
}
