<?php

namespace App\Models\POS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnModel extends Model
{
    use HasFactory;

    protected $table = 'pos_returns';

    protected $fillable = [
        'sale_id',
        'sale_item_id',
        'product_id',
        'quantity',
        'refund_method',
        'refund_amount',
        'created_at',
        'updated_at',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }
}
