<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('boarding_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Cage A1"
            $table->enum('type', ['kennel', 'cage', 'room', 'unit']);
            $table->enum('size', ['small', 'medium', 'large', 'extra-large']);
            $table->unsignedTinyInteger('max_occupants')->default(1);
            $table->decimal('price_per_night', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_units');
    }
};
