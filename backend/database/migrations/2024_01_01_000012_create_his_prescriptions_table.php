<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_prescriptions. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_prescriptions', function (Blueprint $table) {
            $table->id('pres_id');
            $table->string('pres_pat_name')->nullable();
            $table->string('pres_pat_age')->nullable();
            $table->string('pres_pat_number')->nullable();
            $table->string('pres_number')->nullable();
            $table->string('pres_pat_addr')->nullable();
            $table->string('pres_pat_type')->nullable();
            $table->timestamp('pres_date')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->string('pres_pat_ailment')->nullable();
            $table->longText('pres_ins')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_prescriptions');
    }
};
