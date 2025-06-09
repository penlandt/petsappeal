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
    Schema::table('appointments_pending', function (Blueprint $table) {
        $table->enum('status', ['Pending', 'Approved', 'Declined'])->default('Pending')->after('location_id');
        $table->string('reason')->nullable()->after('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('appointments_pending', function (Blueprint $table) {
        $table->dropColumn(['status', 'reason']);
    });
}
};
