<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('email_settings', function (Blueprint $table) {
            $table->boolean('send_receipts_automatically')->default(false);
            $table->boolean('send_invoices_automatically')->default(false);
            $table->boolean('send_appointment_reminders')->default(false);
            $table->boolean('send_reservation_reminders')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('email_settings', function (Blueprint $table) {
            $table->dropColumn([
                'send_receipts_automatically',
                'send_invoices_automatically',
                'send_appointment_reminders',
                'send_reservation_reminders',
            ]);
        });
    }
};
