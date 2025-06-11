<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use Carbon\Carbon;

class DeactivateExpiredCompanies extends Command
{
    protected $signature = 'companies:deactivate-expired';

    protected $description = 'Deactivate companies whose trials have expired and are not subscribed';

    public function handle()
    {
        $now = Carbon::now();

        $expiredCompanies = Company::where('active', true)
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', $now)
            ->get()
            ->filter(function ($company) {
                return !$company->subscribed('default');
            });

        foreach ($expiredCompanies as $company) {
            $company->active = false;
            $company->save();
            $this->info("Deactivated company: {$company->name} (ID: {$company->id})");
        }

        $this->info("Done. Deactivated {$expiredCompanies->count()} companies.");
    }
}
