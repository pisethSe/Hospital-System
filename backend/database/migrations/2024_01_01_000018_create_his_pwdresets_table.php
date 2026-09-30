<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_pwdresets (password reset requests). Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_pwdresets', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('token');
            $table->string('status')->default('Pending');
            $table->string('pwd');
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_pwdresets');
    }
};
