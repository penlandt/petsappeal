<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pos_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('sale_id')->after('id');

            // Optional: add a foreign key if your sales table is `pos_sales`
            $table->foreign('sale_id')->references('id')->on('pos_sales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pos_returns', function (Blueprint $table) {
            $table->dropForeign(['sale_id']);
            $table->dropColumn('sale_id');
        });
    }
};
