<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pos_sale_items', function (Blueprint $table) {
            $table->decimal('points_earned', 8, 2)->default(0);
            $table->decimal('points_redeemed', 8, 2)->default(0);
            $table->integer('returned_quantity')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pos_sale_items', function (Blueprint $table) {
            $table->dropColumn(['points_earned', 'points_redeemed', 'returned_quantity']);
        });
    }
};
