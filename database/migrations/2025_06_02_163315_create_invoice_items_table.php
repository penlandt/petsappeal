<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('invoice_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('invoice_id');
        $table->string('item_type'); // e.g. Grooming, Boarding, POS
        $table->unsignedBigInteger('item_id'); // ID in the source module (e.g. appointment_id)
        $table->string('description');
        $table->integer('quantity')->default(1);
        $table->decimal('unit_price', 10, 2)->default(0);
        $table->decimal('total_price', 10, 2)->default(0);
        $table->decimal('tax_amount', 10, 2)->default(0);
        $table->timestamps();

        $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
