<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('consultation_transactions', function (Blueprint $table) {
      $table->id();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->foreignId('consultation_id')->constrained('consultations');
      $table->string('status', 20);
      $table->string('merchant', 10);
      $table->string('affiliation', 15);
      $table->string('transaction_type', 25);
      $table->string('card_number', 20);
      $table->string('bank_code', 10);
      $table->string('card_product', 5);
      $table->string('authorization_code', 20);
      $table->string('reading_mode', 5);
      $table->string('arqc', 20);
      $table->string('aid', 20);
      $table->string('financial_reference', 20);
      $table->string('terminal_number', 10);
      $table->string('transaction_sequence', 20);
      $table->string('cardholder_name', 100);
      $table->string('legend', 255);
      $table->string('response_code', 5);
      $table->boolean('is_points_used')->default(false);
      $table->decimal('points_redeemed', 11, 2)->nullable()->default(null);
      $table->decimal('amount_redeemed', 11, 2)->nullable()->default(null);
      $table->decimal('previous_balance_amount', 11, 2)->nullable()->default(null);
      $table->decimal('previous_balance_points', 11, 2)->nullable()->default(null);
      $table->decimal('current_balance_amount', 11, 2)->nullable()->default(null);
      $table->decimal('current_balance_points', 11, 2)->nullable()->default(null);
      $table->boolean('is_credit')->default(false);

    });
  }

  public function down(): void {
    Schema::dropIfExists('consultation_transactions');
  }
};
