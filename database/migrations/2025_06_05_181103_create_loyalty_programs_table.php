<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyProgramsTable extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->decimal('points_per_dollar', 5, 2)->default(1.00);
            $table->decimal('point_value', 8, 4)->default(0.05); // $0.05
            $table->decimal('max_discount_percent', 5, 2)->default(5.00); // 5%
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_programs');
    }
}
