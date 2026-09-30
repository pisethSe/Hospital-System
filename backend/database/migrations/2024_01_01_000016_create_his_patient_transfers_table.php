<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_patient_transfers. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_patient_transfers', function (Blueprint $table) {
            $table->id('t_id');
            $table->string('t_hospital')->nullable();
            $table->string('t_date')->nullable();
            $table->string('t_pat_name')->nullable();
            $table->string('t_pat_number')->nullable();
            $table->string('t_status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_patient_transfers');
    }
};
