<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyPointTransactionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->decimal('points', 10, 2);
            $table->enum('type', ['earn', 'redeem']);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_point_transactions');
    }
}
