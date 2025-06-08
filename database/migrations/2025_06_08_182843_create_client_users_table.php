<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('client_users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');

            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->unique(['company_id', 'email']); // Enforce unique email per company
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_users');
    }
}
