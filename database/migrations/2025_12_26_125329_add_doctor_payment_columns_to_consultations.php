<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('consultations', function (Blueprint $table) {
      $table->decimal('doctor_payout_amount',11,2)->nullable();
      $table->dateTime('doctor_paid_at')->nullable();
    });
  }

  public function down(): void {
    Schema::table('consultations', function (Blueprint $table) {
      $table->dropColumn('doctor_payout_amount');
      $table->dropColumn('doctor_paid_at');
    });
  }
};
