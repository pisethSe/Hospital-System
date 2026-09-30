<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_vendor. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_vendor', function (Blueprint $table) {
            $table->id('v_id');
            $table->string('v_number')->nullable();
            $table->string('v_name')->nullable();
            $table->string('v_adr')->nullable();
            $table->string('v_mobile')->nullable();
            $table->string('v_email')->nullable();
            $table->string('v_phone')->nullable();
            $table->text('v_desc')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_vendor');
    }
};
