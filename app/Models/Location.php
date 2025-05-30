<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'timezone',
        'phone',
        'email',
        'inactive',
        'company_id',
        'product_tax_rate',
        'service_tax_rate',
    ];
    

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
