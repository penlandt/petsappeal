<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments_pending', function (Blueprint $table) {
            $table->id();
            
            // Location determines the company
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();

            // Pet gives us client + client_user
            $table->foreignId('pet_id')->constrained()->cascadeOnDelete();

            $table->foreignId('service_id')->constrained()->cascadeOnDelete();

            $table->date('date');
            $table->time('time');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments_pending');
    }
};
