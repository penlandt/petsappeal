<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('pos_returns', function (Blueprint $table) {
        $table->decimal('tax_amount', 8, 2)->default(0)->after('price');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('pos_returns', function (Blueprint $table) {
        $table->dropColumn('tax_amount');
    });
}
};
