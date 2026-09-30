<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_docs (doctor accounts / employees). Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_docs', function (Blueprint $table) {
            $table->id('doc_id');
            $table->string('doc_fname')->nullable();
            $table->string('doc_lname')->nullable();
            $table->string('doc_email')->nullable();
            $table->string('doc_pwd')->nullable();
            $table->string('doc_dept')->nullable();
            $table->string('doc_number')->nullable();
            $table->string('doc_dpic')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_docs');
    }
};
