<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnItem extends Model
{
    use HasFactory;

    protected $table = 'pos_return_items';

    protected $fillable = [
        'return_id',
        'sale_item_id',
        'product_id',
        'quantity',
        'price',
        'tax',
        'line_total',
    ];

    public function return()
    {
        return $this->belongsTo(ReturnTransaction::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }
}
