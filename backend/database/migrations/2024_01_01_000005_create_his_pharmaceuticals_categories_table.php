<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_pharmaceuticals_categories. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_pharmaceuticals_categories', function (Blueprint $table) {
            $table->id('pharm_cat_id');
            $table->string('pharm_cat_name')->nullable();
            $table->string('pharm_cat_vendor')->nullable();
            $table->text('pharm_cat_desc')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_pharmaceuticals_categories');
    }
};
