<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;

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

    public function applyAsMailConfig()
    {
        $required = [
            'host', 'port', 'username', 'password', 'from_email', 'from_name'
        ];

        foreach ($required as $field) {
            if (empty($this->{$field})) {
                throw new \Exception("Missing required email setting: {$field}");
            }
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $this->host);
        Config::set('mail.mailers.smtp.port', $this->port);
        Config::set('mail.mailers.smtp.username', $this->username);
        Config::set('mail.mailers.smtp.password', $this->password);
        Config::set('mail.mailers.smtp.encryption', $this->encryption ?? null);
        Config::set('mail.from.address', $this->from_email);
        Config::set('mail.from.name', $this->from_name);
    }
}
