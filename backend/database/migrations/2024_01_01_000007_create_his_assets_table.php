<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_assets. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_assets', function (Blueprint $table) {
            $table->id('asst_id');
            $table->string('asst_name')->nullable();
            $table->longText('asst_desc')->nullable();
            $table->string('asst_vendor')->nullable();
            $table->string('asst_status')->nullable();
            $table->string('asst_dept')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_assets');
    }
};
