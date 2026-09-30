<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_patients. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_patients', function (Blueprint $table) {
            $table->id('pat_id');
            $table->string('pat_fname')->nullable();
            $table->string('pat_lname')->nullable();
            $table->string('pat_dob')->nullable();
            $table->string('pat_age')->nullable();
            $table->string('pat_number')->nullable();
            $table->string('pat_addr')->nullable();
            $table->string('pat_phone')->nullable();
            $table->string('pat_type')->nullable();
            $table->timestamp('pat_date_joined')->useCurrent();
            $table->timestamp('pat_walk_out_date')->nullable();
            $table->string('pat_ailment')->nullable();
            $table->string('pat_room_number', 50)->nullable();
            // Referenced by the legacy discharge handler (his_admin_discharge_single_patient.php)
            $table->string('pat_discharge_status')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_patients');
    }
};
