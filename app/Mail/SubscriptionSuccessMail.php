<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $planName;

    /**
     * Create a new message instance.
     */
    public function __construct($company, $planName)
    {
        $this->company = $company;
        $this->planName = $planName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🎉 Welcome to PETSAppeal!')
                    ->view('emails.subscription-success')
                    ->with([
                        'companyName' => $this->company->name,
                        'planName' => $this->planName,
                    ]);
    }
}
