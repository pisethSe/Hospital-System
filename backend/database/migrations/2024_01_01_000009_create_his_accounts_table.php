<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_accounts (payable / receivable accounts). Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_accounts', function (Blueprint $table) {
            $table->id('acc_id');
            $table->string('acc_name')->nullable();
            $table->text('acc_desc')->nullable();
            $table->string('acc_type')->nullable();
            $table->string('acc_number')->nullable();
            $table->string('acc_amount')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_accounts');
    }
};
