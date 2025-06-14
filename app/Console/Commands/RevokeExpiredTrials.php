<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\CompanyModuleAccess;
use App\Models\CompanySubscribedModule;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class RevokeExpiredTrials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:revoke-expired-trials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revokes expired trial module access for companies that have not subscribed.';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $now = Carbon::now();

    $expiredCompanies = Company::whereNotNull('trial_ends_at')
        ->where('trial_ends_at', '<', $now)
        ->get();

    foreach ($expiredCompanies as $company) {
        $subscribed = $company->subscribedModules->pluck('module')->toArray();

        $company->moduleAccess
            ->reject(fn($access) => in_array($access->module, $subscribed))
            ->each(function ($access) {
                Log::info("Revoking expired trial access", [
                    'company_id' => $access->company_id,
                    'module' => $access->module,
                ]);

                $access->delete();
            });
    }

    $this->info('Expired trial access has been revoked for non-subscribed modules.');
}

}
