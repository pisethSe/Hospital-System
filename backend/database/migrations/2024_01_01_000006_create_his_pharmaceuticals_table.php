<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_pharmaceuticals. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_pharmaceuticals', function (Blueprint $table) {
            $table->id('phar_id');
            $table->string('phar_name')->nullable();
            $table->string('phar_bcode')->nullable();
            $table->text('phar_desc')->nullable();
            $table->string('phar_qty')->nullable();
            $table->string('phar_cat')->nullable();
            $table->string('phar_vendor')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_pharmaceuticals');
    }
};
