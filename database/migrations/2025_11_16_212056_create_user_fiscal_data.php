<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('user_fiscal_data', function (Blueprint $table) {
      $table->id();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users')->nullable();
      $table->foreignId('updated_by_id')->constrained('users')->nullable();
      $table->foreignId('user_id')->constrained('users')->unique();
      $table->string('code', 13);
      $table->string('name', 75);
      $table->string('zip', 5);
      $table->foreignId('fiscal_regime_id')->constrained('fiscal_regimes');
    });
  }

  public function down(): void {
    Schema::dropIfExists('user_fiscal_data');
  }
};
