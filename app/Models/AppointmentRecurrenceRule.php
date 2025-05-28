<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentRecurrenceRule extends Model
{
    use HasFactory;

    protected $table = 'appointment_recurrence_rules';

    protected $fillable = [
        'recurrence_group_id',
        'location_id',
        'staff_id',
        'pet_id',
        'service_id',
        'price',
        'repeat_type',
        'repeat_interval',
        'start_date',
        'start_time',
        'notes',
    ];
}
