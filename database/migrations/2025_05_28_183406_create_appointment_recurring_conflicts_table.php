<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentRecurringConflictsTable extends Migration
{
    public function up()
    {
        Schema::create('appointment_recurring_conflicts', function (Blueprint $table) {
            $table->id();
            $table->uuid('recurrence_group_id');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->date('conflict_date');
            $table->time('conflict_time');
            $table->string('reason'); // e.g. 'Unavailable', 'Exception'
            $table->boolean('resolved')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointment_recurring_conflicts');
    }
}
