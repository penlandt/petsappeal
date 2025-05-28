<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['location_id']);
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['pet_id']);
            $table->dropForeign(['service_id']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            // Re-add foreign keys with ON DELETE CASCADE
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['pet_id']);
            $table->dropForeign(['service_id']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('staff_id')->references('id')->on('staff');
            $table->foreign('pet_id')->references('id')->on('pets');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }
};
