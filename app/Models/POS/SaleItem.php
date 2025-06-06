<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory;

    protected $table = 'pos_sale_items';

    protected $fillable = [
        'sale_id',
        'product_id',
        'name',
        'price',
        'quantity',
        'line_total',
        'tax_amount',
        'points_earned', 
        'points_redeemed',
        'returned_quantity',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
