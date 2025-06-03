<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosReturnsTable extends Migration
{
    public function up(): void
    {
        Schema::create('pos_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable(); // allow null if no client selected
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->string('refund_method'); // e.g., Cash, Credit, Store Credit
            $table->text('notes')->nullable();
            $table->foreignId('location_id')->constrained(); // links to current selected location
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_returns');
    }
}
