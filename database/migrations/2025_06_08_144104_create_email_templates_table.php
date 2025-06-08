<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->enum('type', ['grooming', 'boarding', 'daycare', 'house/pet sitting']);
            $table->string('template_key'); // e.g., appointment_booked, reservation_reminder
            $table->string('subject');
            $table->longText('body_html');
            $table->longText('body_plain');
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');

            $table->unique(['company_id', 'type', 'template_key'], 'email_template_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
