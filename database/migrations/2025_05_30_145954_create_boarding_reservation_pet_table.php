<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('boarding_reservation_pet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boarding_reservation_id')->constrained('boarding_reservations')->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['boarding_reservation_id', 'pet_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_reservation_pet');
    }
};
