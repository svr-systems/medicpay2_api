<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('hospitals', function (Blueprint $table) {
      $table->id();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->string('subdomain', 30)->unique();
      $table->string('name', 100);
      $table->tinyInteger('fee')->unsigned();
      $table->string('logo_path', 50)->nullable();
      $table->string('zip', 5)->nullable();
      $table->foreignId('municipality_id')->nullable()->constrained('municipalities');
      $table->string('street', 75)->nullable();
      $table->string('exterior', 15)->nullable();
      $table->string('interior', 15)->nullable();
      $table->string('neighborhood', 75)->nullable();
    });
  }

  public function down(): void {
    Schema::dropIfExists('hospitals');
  }
};
