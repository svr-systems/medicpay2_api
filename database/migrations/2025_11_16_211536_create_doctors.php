<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('doctors', function (Blueprint $table) {
      $table->id();
      $table->boolean('is_active')->default(true);
      $table->foreignId('user_id')->constrained('users')->unique();
      $table->foreignId('hospital_id')->constrained('hospitals');
    });
  }

  public function down(): void {
    Schema::dropIfExists('doctors');
  }
};
