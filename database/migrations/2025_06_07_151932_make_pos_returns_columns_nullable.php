<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePosReturnsColumnsNullable extends Migration
{
    public function up(): void
    {
        Schema::table('pos_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->decimal('quantity', 8, 2)->nullable()->change();
            $table->decimal('price', 8, 2)->nullable()->change();
            $table->decimal('tax_amount', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pos_returns', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->decimal('quantity', 8, 2)->nullable(false)->change();
            $table->decimal('price', 8, 2)->nullable(false)->change();
            $table->decimal('tax_amount', 8, 2)->nullable(false)->change();
        });
    }
}
