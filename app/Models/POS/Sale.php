<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'pos_sales';

    protected $fillable = [
        'company_id',
        'location_id',
        'client_id',
        'subtotal',
        'tax',
        'total',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'sale_id');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }
    
}
