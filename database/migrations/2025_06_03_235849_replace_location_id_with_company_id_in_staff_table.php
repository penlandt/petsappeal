<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add company_id column (nullable for now)
        Schema::table('staff', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
        });

        // Step 2: Backfill company_id from related locations
        DB::statement('
            UPDATE staff
            JOIN locations ON staff.location_id = locations.id
            SET staff.company_id = locations.company_id
        ');

        // Step 3: Drop location_id column
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['location_id']); // In case it exists
            $table->dropColumn('location_id');
        });

        // Step 4: Make company_id not nullable and add FK
        Schema::table('staff', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('company_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable()->after('id');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
