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
        'taxable',
        'quantity',
        'inactive',
    ];

    public function inventories()
    {
        return $this->hasMany(\App\Models\ProductInventory::class);
    }
    
    public function inventoryForLocation($locationId)
    {
        return $this->inventories()->where('location_id', $locationId)->first();
    }
    
}
