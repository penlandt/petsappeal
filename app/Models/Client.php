<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Modules\Invoices\Invoice::class);
    }
    
}
