<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::table('invoice_items', function (Blueprint $table) {
        // Only add the foreign key, not the column (it already exists)
        $table->foreign('appointment_id')
            ->references('appointment_id')
            ->on('appointments')
            ->nullOnDelete();
    });
}



public function down(): void
{
    Schema::table('invoice_items', function (Blueprint $table) {
        $table->dropForeign(['appointment_id']);
        $table->dropColumn('appointment_id');
    });
}

};
