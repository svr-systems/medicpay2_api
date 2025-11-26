<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class DoctorSpecialty extends Model {
  use HasFactory;
  public $timestamps = false;

  public static function validValidation($data) {
    $rules = [
      'id' => 'required|numeric',
      'is_valid' => 'required|boolean',
      // 'bank_validated_path' => 'required|file',
    ];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }
}
