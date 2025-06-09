<?php

namespace App\Mail;

use App\Models\ClientUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientPortalWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public ClientUser $clientUser;
    public string $plainPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(ClientUser $clientUser, string $plainPassword)
    {
        $this->clientUser = $clientUser;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Build the message.
     */
    public function build()
{
    $company = $this->clientUser->company;
    $companyName = $company->name ?? 'Your Groomer';
    $companySlug = $company->slug ?? 'unknown';
    $loginUrl = "https://pets-appeal.com/book/{$companySlug}";

    return $this->subject("Welcome to {$companyName} — Client Portal Access")
                ->view('emails.client-portal-welcome')
                ->with([
                    'clientUser' => $this->clientUser,
                    'plainPassword' => $this->plainPassword,
                    'loginUrl' => $loginUrl,
                    'companyName' => $companyName,
                ]);
}


}
