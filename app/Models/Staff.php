<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\StaffAvailability;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'type',
        'first_name',
        'last_name',
        'job_title',
        'address',
        'city',
        'state',
        'postal_code',
        'phone',
        'email',
        'start_date',
        'end_date',
        'notes',
    ];

    public function availabilities()
    {
        return $this->hasMany(\App\Models\StaffAvailability::class);
    }

    public function availabilityExceptions()
    {
        return $this->hasMany(AvailabilityException::class);
    }

    public function availability()
    {
        return $this->hasMany(StaffAvailability::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }


}
