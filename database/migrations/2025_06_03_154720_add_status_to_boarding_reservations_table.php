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
    Schema::table('boarding_reservations', function (Blueprint $table) {
        $table->enum('status', ['Booked', 'Confirmed', 'Cancelled', 'No-Show', 'Checked In', 'Checked Out'])
              ->default('Booked')
              ->after('price_total');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('boarding_reservations', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
