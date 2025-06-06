<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailSetting extends Model
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
    

    protected $hidden = [
        'password',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getPasswordAttribute($value)
{
    try {
        return decrypt($value);
    } catch (\Exception $e) {
        // If it fails to decrypt (e.g., old unencrypted value), return raw
        return $value;
    }
}

}
