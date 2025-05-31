<?php

namespace App\Models\Modules\Boarding;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoardingReservation extends Model
{
    use HasFactory;

    protected $table = 'boarding_reservations';

    protected $fillable = [
        'boarding_unit_id',
        'client_id',
        'check_in_date',
        'check_out_date',
        'price_total',
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
    
}
