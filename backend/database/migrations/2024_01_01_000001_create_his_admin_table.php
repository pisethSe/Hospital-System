<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_admin (admin accounts). Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_admin', function (Blueprint $table) {
            $table->id('ad_id');
            $table->string('ad_fname')->nullable();
            $table->string('ad_lname')->nullable();
            $table->string('ad_email')->nullable();
            $table->string('ad_pwd')->nullable();
            $table->string('ad_dpic', 1000)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_admin');
    }
};
