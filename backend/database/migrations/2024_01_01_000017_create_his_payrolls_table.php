<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Legacy table: his_payrolls. Column names preserved.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('his_payrolls', function (Blueprint $table) {
            $table->id('pay_id');
            $table->string('pay_number')->nullable();
            $table->string('pay_doc_name')->nullable();
            $table->string('pay_doc_number')->nullable();
            $table->string('pay_doc_email')->nullable();
            $table->string('pay_emp_salary')->nullable();
            $table->timestamp('pay_date_generated')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->string('pay_status')->nullable();
            $table->longText('pay_descr')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('his_payrolls');
    }
};
