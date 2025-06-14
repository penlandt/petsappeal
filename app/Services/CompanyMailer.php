<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Mail\Mailer;
use App\Models\EmailSetting;

class CompanyMailer
{
    protected Mailer $mailer;

    public function __construct()
    {
        $this->applyCompanySmtpSettings();
        $this->mailer = Mail::mailer();
    }

    public static function to($address)
    {
        return (new static())->mailer->to($address);
    }

    public static function send(Mailable $mailable)
    {
        return (new static())->mailer->send($mailable);
    }

    public function applyCompanySmtpSettings(): void
    {
        if (!app()->environment('production')) {
            return;
        }

        $user = Auth::user();

        if (!$user || $user->is_admin) {
            // Admin or unauthenticated: use system settings
            return;
        }

        $emailSettings = EmailSetting::where('company_id', $user->company_id)->first();

        if (!$emailSettings) {
            throw new \Exception('Email settings not found for your company.');
        }

        $required = [
            'host', 'port', 'username', 'password', 'from_email', 'from_name'
        ];

        foreach ($required as $field) {
            if (empty($emailSettings->{$field})) {
                throw new \Exception("Missing required email setting: {$field}");
            }
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $emailSettings->host);
        Config::set('mail.mailers.smtp.port', $emailSettings->port);
        Config::set('mail.mailers.smtp.username', $emailSettings->username);
        Config::set('mail.mailers.smtp.password', $emailSettings->password);
        Config::set('mail.mailers.smtp.encryption', $emailSettings->encryption ?? null);
        Config::set('mail.from.address', $emailSettings->from_email);
        Config::set('mail.from.name', $emailSettings->from_name);
    }
}
