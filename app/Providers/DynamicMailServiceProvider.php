<?php

namespace App\Providers;

use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use App\Models\EmailSetting;

class DynamicMailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->extend('mail.manager', function (MailManager $manager) {
            $manager->extend('dynamic', function () {
                $user = Auth::user();
                $company = $user?->company;

                if (!$company) {
                    throw new \RuntimeException('No company context found for dynamic mailer.');
                }

                $settings = EmailSetting::where('company_id', $company->id)->firstOrFail();

                $encryption = $settings->encryption ?: 'tls';

                $dsn = new Dsn(
                    'smtp',
                    $settings->host,
                    $settings->username,
                    $settings->password,
                    $settings->port,
                    $encryption
                );

                $transportFactory = new EsmtpTransportFactory();
                return $transportFactory->create($dsn);
            });

            return $manager;
        });
    }
}
