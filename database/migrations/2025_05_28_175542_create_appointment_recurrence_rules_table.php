<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentRecurrenceRulesTable extends Migration
{
    public function up()
    {
        Schema::create('appointment_recurrence_rules', function (Blueprint $table) {
            $table->id();
            $table->uuid('recurrence_group_id')->unique();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 8, 2)->nullable();
            $table->string('repeat_type'); // 'weekly' or 'monthly'
            $table->integer('repeat_interval'); // e.g. 1 = every week, 2 = every 2 weeks
            $table->date('start_date');
            $table->time('start_time');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointment_recurrence_rules');
    }
}
