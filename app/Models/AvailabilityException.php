<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailabilityException extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
