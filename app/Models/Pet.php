<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'species',
        'breed',
        'birthdate',
        'color',
        'gender',
        'notes',
        'inactive',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function boardingReservations()
{
    return $this->belongsToMany(\App\Models\Modules\Boarding\BoardingReservation::class, 'boarding_reservation_pet');
}

}
