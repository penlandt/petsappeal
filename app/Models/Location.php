<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'phone',
        'email',
        'inactive',
        'timezone',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
