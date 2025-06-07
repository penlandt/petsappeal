<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'from_name',
        'from_email',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'send_receipts_automatically',
        'send_invoices_automatically',
        'send_appointment_reminders',
        'send_reservation_reminders',
    ];

    protected $casts = [
        'send_receipts_automatically'   => 'boolean',
        'send_invoices_automatically'   => 'boolean',
        'send_appointment_reminders'    => 'boolean',
        'send_reservation_reminders'    => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
