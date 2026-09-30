<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_medical_records. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_medical_records', function (Blueprint $table) {
            $table->id('mdr_id');
            $table->string('mdr_number')->nullable();
            $table->string('mdr_pat_name')->nullable();
            $table->string('mdr_pat_adr')->nullable();
            $table->string('mdr_pat_age')->nullable();
            $table->string('mdr_pat_ailment')->nullable();
            $table->string('mdr_pat_number')->nullable();
            $table->longText('mdr_pat_prescr')->nullable();
            $table->timestamp('mdr_date_rec')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_medical_records');
    }
};
