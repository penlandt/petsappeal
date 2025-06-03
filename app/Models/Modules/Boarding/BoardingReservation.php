<?php

namespace App\Models\Modules\Boarding;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoardingReservation extends Model
{
    use HasFactory;

    protected $table = 'boarding_reservations';

    protected $fillable = [
        'location_id',
        'client_id',
        'boarding_unit_id',
        'check_in_date',
        'check_out_date',
        'price_total',
        'notes',
        'status',
    ];

    public function boardingUnit()
    {
        return $this->belongsTo(BoardingUnit::class);
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class);
    }

    public function pets()
    {
        return $this->belongsToMany(\App\Models\Pet::class, 'boarding_reservation_pet');
    }

    public function location()
{
    return $this->hasOneThrough(
        \App\Models\Location::class,
        \App\Models\Modules\Boarding\BoardingUnit::class,
        'id',            // Foreign key on BoardingUnit
        'id',            // Foreign key on Location
        'boarding_unit_id', // Local key on Reservation
        'location_id'    // Local key on BoardingUnit
    );
}

}
