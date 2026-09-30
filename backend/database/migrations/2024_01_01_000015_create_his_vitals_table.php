<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_vitals. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_vitals', function (Blueprint $table) {
            $table->id('vit_id');
            $table->string('vit_number')->nullable();
            $table->string('vit_pat_number')->nullable();
            $table->string('vit_bodytemp')->nullable();
            $table->string('vit_heartpulse')->nullable();
            $table->string('vit_resprate')->nullable();
            $table->string('vit_bloodpress')->nullable();
            $table->timestamp('vit_daterec')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_vitals');
    }
};
