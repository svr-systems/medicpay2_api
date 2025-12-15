<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('consultations', function (Blueprint $table) {
      $table->renameColumn('invoice_id', 'patient_invoice_id');
      $table->string('doctor_invoice_id', 25)->nullable()->default(null);
    });
  }

  public function down(): void {
    Schema::table('consultations', function (Blueprint $table) {
      $table->renameColumn('patient_invoice_id', 'invoice_id');
      $table->dropColumn('doctor_invoice_id');
    });
  }
};
