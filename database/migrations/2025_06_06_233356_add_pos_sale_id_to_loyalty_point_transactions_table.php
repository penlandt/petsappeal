<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loyalty_point_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('pos_sale_id')->nullable()->after('company_id');
            $table->foreign('pos_sale_id')->references('id')->on('pos_sales')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('loyalty_point_transactions', function (Blueprint $table) {
            $table->dropForeign(['pos_sale_id']);
            $table->dropColumn('pos_sale_id');
        });
    }
};
