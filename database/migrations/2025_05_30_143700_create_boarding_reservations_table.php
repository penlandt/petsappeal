<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('boarding_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boarding_unit_id')->constrained('boarding_units')->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->decimal('price_total', 8, 2)->nullable(); // Calculated during creation
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_reservations');
    }
};
