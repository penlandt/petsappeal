<?php

namespace App\Models\Modules\Boarding;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoardingUnit extends Model
{
    use HasFactory;

    protected $table = 'boarding_units';

    protected $fillable = [
        'location_id',
        'name',
        'type',
        'size',
        'max_occupants',
        'price_per_night',
    ];

    // Relationships
    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class);
    }
}
