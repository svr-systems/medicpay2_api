<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('consultations', function (Blueprint $table) {
      $table->id();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->foreignId('doctor_id')->constrained('doctors');
      $table->foreignId('patient_id')->constrained('patients');
      $table->decimal('consultation_amount', 11, 2);
      $table->decimal('charge_amount', 11, 2);

    });
  }
  public function down(): void {
    Schema::dropIfExists('consultations');
  }
};
