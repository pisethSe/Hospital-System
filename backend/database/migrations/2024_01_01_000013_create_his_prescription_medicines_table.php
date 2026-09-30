<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Legacy table: his_prescription_medicines (medicines attached to a
 * prescription). Column names preserved.
 *
 * Note: the legacy schema declared a foreign key on pres_number as INT
 * while his_prescriptions.pres_number is a VARCHAR, which MySQL rejects.
 * The row is tied to its prescription through pres_id (same behaviour),
 * so pres_number is kept as a plain indexed string column.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_prescription_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pres_id')->constrained('his_prescriptions', 'pres_id')->cascadeOnDelete();
            $table->string('pres_number')->nullable()->index();
            $table->string('medicine_name');
            $table->string('medicine_qty', 100);
            $table->string('medicine_time', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_prescription_medicines');
    }
};
