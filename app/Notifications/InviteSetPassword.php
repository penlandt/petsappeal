<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class InviteSetPassword extends Notification
{
    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('You’ve been invited to PETSAppeal')
            ->greeting("Hello {$notifiable->name},")
            ->line("You've been invited to join PETSAppeal — the pet grooming and retail management platform.")
            ->line("To get started, please set your password using the link below.")
            ->action('Set Your Password', $resetUrl)
            ->line('If you were not expecting this email, you can ignore it.')
            ->salutation('— The PETSAppeal Team');
    }
}
