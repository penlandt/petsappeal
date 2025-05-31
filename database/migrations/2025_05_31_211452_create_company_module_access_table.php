<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_module_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('module'); // e.g. 'boarding', 'grooming', 'pos'
            $table->timestamps();

            $table->unique(['company_id', 'module']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_module_access');
    }
};
