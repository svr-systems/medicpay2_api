<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('user_bank_data', function (Blueprint $table) {
      $table->id();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->foreignId('user_id')->constrained('users')->unique();
      $table->foreignId('bank_type_id')->constrained('bank_types');
      $table->string('bank_account', 15);
      $table->string('bank_clabe', 18);
      $table->boolean('is_valid')->nullable();
      $table->foreignId('validated_by_id')->nullable()->constrained('users');
      $table->dateTime('validated_at')->nullable();
      $table->string('bank_validated_path', 50)->nullable();
    });
  }

  public function down(): void {
    Schema::dropIfExists('user_bank_data');
  }
};
