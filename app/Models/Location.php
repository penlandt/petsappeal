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
        'boarding_check_in_time',
        'boarding_check_out_time',
        'boarding_chg_per_addl_occpt',
    ];
    

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
