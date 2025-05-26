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
    Schema::table('locations', function (Blueprint $table) {
        $table->string('timezone')->default('America/Los_Angeles')->after('email');
    });
}

public function down()
{
    Schema::table('locations', function (Blueprint $table) {
        $table->dropColumn('timezone');
    });
}
};
