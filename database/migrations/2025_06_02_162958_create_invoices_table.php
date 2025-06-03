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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('client_id')->nullable();
        $table->unsignedBigInteger('location_id');
        $table->date('invoice_date');
        $table->date('due_date')->nullable();
        $table->decimal('total_amount', 10, 2)->default(0);
        $table->decimal('amount_paid', 10, 2)->default(0);
        $table->enum('status', ['Unpaid', 'Partial', 'Paid', 'Voided'])->default('Unpaid');
        $table->string('payment_method')->nullable(); // Optional if paying immediately
        $table->timestamps();

        $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
