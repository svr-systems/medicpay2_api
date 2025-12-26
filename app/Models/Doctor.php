<?php

namespace App\Models;

use App\Http\Controllers\DocMgrController;
use App\Http\Controllers\GenController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Doctor extends Model {
  use HasFactory;
  public $timestamps = false;

  static public function getUiid($id) {
    return 'DR-' . str_pad($id, 4, '0', STR_PAD_LEFT);
  }

  public static function validValidation($data) {
    $rules = [
      'id' => 'required|numeric',
      'is_valid' => 'required|boolean',
      // 'bank_validated_path' => 'required|file',
    ];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }

  static public function getItems($req) {
    $items = Doctor::
      join('users', 'doctors.user_id', 'users.id')->
    where('users.is_active', boolval($req->is_active));

    $items = $items->
      get([
        'doctors.id',
        'users.is_active',
        'user_id',
        'hospital_id',
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->uiid = Doctor::getUiid($item->id);
      $item->user = User::find($item->user_id);
      $item->user->full_name = GenController::getFullName($item->user);
      $item->hospital = Hospital::find($item->hospital_id);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = Doctor::
      find($id, [
        'id',
        'user_id',
        'hospital_id',
        'specialty_id',
      ]);

    if ($item) {
      $item->uiid = Doctor::getUiid($item->id);
      $item->user = User::getItem(null, $item->user_id);
      $item->user->full_name = GenController::getFullName($item->user);
      $item->hospital = Hospital::find($item->hospital_id);
      $item->specialty = Specialty::find($item->specialty_id);
    }

    return $item;
  }

  static public function getItemByUserId($user_id){
    $item = Doctor::where('user_id',$user_id)
      ->first();

    $item->user = User::find($user_id);

    return $item;
  }
}
