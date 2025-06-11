<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::table('companies', function (Blueprint $table) {
        if (!Schema::hasColumn('companies', 'stripe_id')) {
            $table->string('stripe_id')->nullable()->index();
        }
        if (!Schema::hasColumn('companies', 'pm_type')) {
            $table->string('pm_type')->nullable();
        }
        if (!Schema::hasColumn('companies', 'pm_last_four')) {
            $table->string('pm_last_four', 4)->nullable();
        }
        if (!Schema::hasColumn('companies', 'trial_ends_at')) {
            $table->timestamp('trial_ends_at')->nullable();
        }
    });
}


    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at']);
        });
    }
};
