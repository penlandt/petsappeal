<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'points_per_dollar',
        'point_value',
        'max_discount_percent',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }    
}
