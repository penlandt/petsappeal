<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentReminder extends Model
{
    use HasFactory;

    protected $table = 'appointment_reminders';

    protected $fillable = [
        'appointment_id',
        'reminder_type',
        'sent_at',
    ];

    public $timestamps = true;
}
