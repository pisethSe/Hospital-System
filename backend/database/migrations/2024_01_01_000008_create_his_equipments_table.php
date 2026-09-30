<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_equipments. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_equipments', function (Blueprint $table) {
            $table->id('eqp_id');
            $table->string('eqp_code')->nullable();
            $table->string('eqp_name')->nullable();
            $table->string('eqp_vendor')->nullable();
            $table->longText('eqp_desc')->nullable();
            $table->string('eqp_dept')->nullable();
            $table->string('eqp_status')->nullable();
            $table->string('eqp_qty')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_equipments');
    }
};
