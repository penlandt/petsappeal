<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionCanceledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $company;

    /**
     * Create a new message instance.
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your PETSAppeal Subscription Has Been Canceled')
                    ->view('emails.subscription-canceled')
                    ->with([
                        'companyName' => $this->company->name,
                    ]);
    }
}
