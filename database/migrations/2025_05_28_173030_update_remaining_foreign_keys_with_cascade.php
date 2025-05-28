<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRemainingForeignKeysWithCascade extends Migration
{
    public function up(): void
    {
        Schema::table('availability_exceptions', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->foreign('staff_id')
                ->references('id')->on('staff')
                ->onDelete('cascade');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');
        });

        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');
        });

        Schema::table('staff_availabilities', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->foreign('staff_id')
                ->references('id')->on('staff')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // No need to reverse cascade deletes in this case
    }
}
