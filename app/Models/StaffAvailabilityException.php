<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAvailabilityException extends Model
{
    use HasFactory;

    protected $table = 'availability_exceptions';
}
