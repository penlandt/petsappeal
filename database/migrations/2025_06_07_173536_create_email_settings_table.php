<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->unique();
            $table->string('from_name');
            $table->string('from_email');
            $table->string('mailer')->default('smtp');
            $table->string('host');
            $table->unsignedSmallInteger('port');
            $table->string('encryption')->nullable(); // ssl, tls, etc.
            $table->string('username');
            $table->string('password'); // we’ll encrypt this
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
