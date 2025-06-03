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
    Schema::table('pos_sales', function (Blueprint $table) {
        $table->unsignedBigInteger('client_id')->nullable()->after('location_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('pos_sales', function (Blueprint $table) {
        $table->dropColumn('client_id');
    });
}
};
