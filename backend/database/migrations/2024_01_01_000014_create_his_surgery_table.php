<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_surgery (theatre records). Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_surgery', function (Blueprint $table) {
            $table->id('s_id');
            $table->string('s_number')->nullable();
            $table->string('s_doc')->nullable();
            $table->string('s_pat_number')->nullable();
            $table->string('s_pat_name')->nullable();
            $table->string('s_pat_ailment')->nullable();
            $table->timestamp('s_pat_date')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->string('s_pat_status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_surgery');
    }
};
