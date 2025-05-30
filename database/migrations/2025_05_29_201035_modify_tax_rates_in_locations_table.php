<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            if (Schema::hasColumn('locations', 'tax_rate')) {
                $table->dropColumn('tax_rate');
            }
            $table->decimal('product_tax_rate', 5, 2)->nullable()->after('name');
            $table->decimal('service_tax_rate', 5, 2)->nullable()->after('product_tax_rate');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['product_tax_rate', 'service_tax_rate']);
            $table->decimal('tax_rate', 5, 2)->nullable()->after('name');
        });
    }
};
