<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pos_returns', function (Blueprint $table) {
            $table->decimal('refund_amount', 10, 2)->default(0)->after('tax_amount');
            $table->decimal('points_redeemed', 10, 2)->default(0)->after('refund_amount');
        });
    }

    public function down(): void
    {
        Schema::table('pos_returns', function (Blueprint $table) {
            $table->dropColumn(['refund_amount', 'points_redeemed']);
        });
    }
};
