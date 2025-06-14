<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\StaffAvailability;
use App\Models\User;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'company_id',
        'user_id',
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
        return $this->hasMany(StaffAvailability::class);
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
