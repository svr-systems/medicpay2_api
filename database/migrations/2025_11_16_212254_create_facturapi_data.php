<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('facturapi_data', function (Blueprint $table) {
      $table->id();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->foreignId('user_fiscal_data_id')->constrained('user_fiscal_data')->unique();
      $table->string('organization_id', 25);
      $table->dateTime('certificate_updated_at');
      $table->dateTime('certificate_expires_at');
      $table->string('certificate_serial_number', 50);
    });
  }

  public function down(): void {
    Schema::dropIfExists('facturapi_data');
  }
};
