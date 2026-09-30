<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_laboratory (lab tests & results). Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_laboratory', function (Blueprint $table) {
            $table->id('lab_id');
            $table->string('lab_pat_name')->nullable();
            $table->string('lab_pat_ailment')->nullable();
            $table->string('lab_pat_number')->nullable();
            $table->longText('lab_pat_tests')->nullable();
            $table->longText('lab_pat_results')->nullable();
            $table->string('lab_number')->nullable();
            $table->timestamp('lab_date_rec')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_laboratory');
    }
};
