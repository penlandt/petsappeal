<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendingAppointment extends Model
{
    use HasFactory;

    protected $table = 'appointments_pending';

    protected $fillable = [
        'location_id',
        'pet_id',
        'service_id',
        'date',
        'time',
        'notes',
        'status',
        'reason',
    ];

    // Relationships
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Optional: Derived accessors
    public function client()
    {
        return $this->pet->client ?? null;
    }

    public function company()
    {
        return $this->location->company ?? null;
    }
}
