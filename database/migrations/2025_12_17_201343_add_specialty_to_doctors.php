<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {

    Schema::table('doctors', function (Blueprint $table) {
      $table->foreignId('specialty_id')->nullable()->constrained('specialties');
      $table->string('license', 20);
      $table->boolean('is_valid')->nullable();
      $table->foreignId('validated_by_id')->nullable()->constrained('users');
      $table->dateTime('validated_at')->nullable();
    });
  }

  public function down(): void {
    Schema::table('doctors', function (Blueprint $table) {
      $table->dropConstrainedForeignId('specialty_id');;
      $table->dropColumn('license');
      $table->dropColumn('is_valid');
      $table->dropConstrainedForeignId('validated_by_id');
      $table->dropColumn('validated_at');
    });
  }
};
