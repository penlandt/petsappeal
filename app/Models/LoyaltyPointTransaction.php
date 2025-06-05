<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyPointTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'company_id',
        'points',
        'type',
        'description',
        'created_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
