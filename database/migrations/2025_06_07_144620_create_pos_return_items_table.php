<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosReturnItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('pos_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('sale_item_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 8, 2);
            $table->decimal('price', 8, 2);
            $table->decimal('tax', 8, 2)->default(0);
            $table->decimal('line_total', 8, 2); // price * qty + tax
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('pos_returns')->onDelete('cascade');
            $table->foreign('sale_item_id')->references('id')->on('pos_sale_items')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_return_items');
    }
}
