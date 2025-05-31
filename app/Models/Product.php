<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'upc',
        'sku',
        'description',
        'cost',
        'price',
        'quantity',
        'inactive',
    ];
}
