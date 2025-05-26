<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'duration',
        'price',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
