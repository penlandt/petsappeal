<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->unique();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('encryption')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();

            // Auto-email settings (default: false)
            $table->boolean('send_receipts_automatically')->default(false);
            $table->boolean('send_invoices_automatically')->default(false);
            $table->boolean('send_appointment_reminders')->default(false);
            $table->boolean('send_reservation_reminders')->default(false);

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
