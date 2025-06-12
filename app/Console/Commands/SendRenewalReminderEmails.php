<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Company;
use App\Models\Subscription;
use App\Mail\SubscriptionRenewalReminderMail;
use App\Services\CompanyMailer;

class SendRenewalReminderEmails extends Command
{
    protected $signature = 'subscriptions:send-renewal-reminders';
    protected $description = 'Send reminder emails to companies whose subscriptions will renew in 3 days';

    public function handle(): void
    {
        $targetDate = Carbon::now()->addDays(3)->startOfDay();

        $subscriptions = Subscription::where('stripe_status', 'active')
            ->whereDate('stripe_current_period_end', $targetDate)
            ->get();

        foreach ($subscriptions as $subscription) {
            $company = Company::find($subscription->company_id);

            if (!$company || !$company->email) {
                continue;
            }

            CompanyMailer::to($company->email)->send(
                new SubscriptionRenewalReminderMail($company, $subscription)
            );

            $this->info("Reminder sent to: {$company->name}");
        }

        $this->info('Subscription renewal reminders processed.');
    }
}
