<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentRecurringConflict extends Model
{
    use HasFactory;

    protected $fillable = [
        'recurrence_group_id',
        'staff_id',
        'conflict_date',
        'conflict_time',
        'reason',
        'resolved',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
