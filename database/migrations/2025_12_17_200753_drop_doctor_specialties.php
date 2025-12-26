<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::dropIfExists('doctor_specialties');
  }

  public function down(): void {
    Schema::create('doctor_specialties', function (Blueprint $table) {
      $table->id();
      $table->boolean('is_active')->default(true);
      $table->foreignId('doctor_id')->constrained('doctors');
      $table->foreignId('specialty_id')->constrained('specialties');
      $table->string('license', 20);
      $table->boolean('is_valid')->nullable();
      $table->foreignId('validated_by_id')->nullable()->constrained('users');
      $table->dateTime('validated_at')->nullable();
    });
  }
};
