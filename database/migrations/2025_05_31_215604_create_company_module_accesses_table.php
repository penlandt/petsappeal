<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyModuleAccessesTable extends Migration
{
    public function up()
    {
        Schema::create('company_module_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('module_name'); // This line was missing
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_module_accesses');
    }
}
