<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            // Drop the company_id foreign key and column if it exists
            if (Schema::hasColumn('staff', 'company_id')) {
                try {
                    $table->dropForeign(['company_id']);
                } catch (\Throwable $e) {
                    // Foreign key may not exist or may have been removed already
                }
                $table->dropColumn('company_id');
            }

            // Add location_id only if it doesn't already exist
            if (!Schema::hasColumn('staff', 'location_id')) {
                $table->unsignedBigInteger('location_id')->nullable()->after('id');
            }
        });

        // Assign each staff to the first location from their original company (if possible)
        $staffMembers = DB::table('staff')->get();

        foreach ($staffMembers as $staff) {
            // Try to infer the company_id from the most likely past setup
            $likelyCompanyId = DB::table('locations')->min('company_id');

            $location = DB::table('locations')
                ->where('company_id', $likelyCompanyId)
                ->orderBy('id')
                ->first();

            if ($location) {
                DB::table('staff')
                    ->where('id', $staff->id)
                    ->update(['location_id' => $location->id]);
            }
        }

        // Make location_id non-nullable and add the foreign key constraint
        Schema::table('staff', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable(false)->change();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');

            $table->unsignedBigInteger('company_id')->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
};
