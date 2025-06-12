<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Company;
use App\Models\Subscription;

class SubscriptionRenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $subscription;

    public function __construct(Company $company, Subscription $subscription)
    {
        $this->company = $company;
        $this->subscription = $subscription;
    }

    public function build()
    {
        return $this->subject('Your PETSAppeal Subscription Will Renew Soon')
                    ->view('emails.subscription-renewal-reminder')
                    ->with([
                        'companyName' => $this->company->name,
                        'planName' => $this->company->plan_name,
                        'renewalDate' => $this->subscription->stripe_current_period_end,
                    ]);
    }
}
