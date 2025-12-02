<?php

namespace App\Models;

use App\Http\Controllers\GenController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model {
  use HasFactory;
  public $timestamps = false;

  static public function getUiid($id) {
    return 'P-' . str_pad($id, 4, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $items = Patient::
      join('users', 'patients.user_id', 'users.id')->
      where('users.is_active', boolval($req->is_active));

    $items = $items->
    get([
        'patients.id',
        'users.is_active',
        'user_id',
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->uiid = Patient::getUiid($item->id);
      $item->user = User::find($item->user_id);
      $item->user->full_name = GenController::getFullName($item->user);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = Patient::
    find($id, [
        'id',
        'user_id',
      ]);

    if ($item) {
      $item->uiid = Patient::getUiid($item->id);
      $item->user = User::getItem(null, $item->user_id);
      $item->user->full_name = GenController::getFullName($item->user);
    }

    return $item;
  }

  static public function search($req) {
    $item = Patient::join('users','users.id','patients.user_id')->
      where('email',$req->email)->
      where('is_active',true)->
      first([
        'patients.id',
        'user_id'
      ]);

    if ($item) {
      $item->uiid = Patient::getUiid($item->id);
      $item->user = User::find($item->user_id,[
        'id',
        'name',
        'paternal_surname',
        'maternal_surname',
        'curp',
        'email',
        'phone',
        'avatar',
      ]);
      $item->user->uiid = User::getUiid($item->user->id);
      $item->user->full_name = GenController::getFullName($item->user);
      $item->user->avatar_doc = null;
      $item->user->avatar_dlt = null;
    }else{
      $item = new \stdClass();

      $item->id = null;
      $item->user_id = null;
      $item->uiid = null;
      $item->user = new \stdClass();
      $item->user->id = null;
      $item->user->name = null;
      $item->user->paternal_surname = null;
      $item->user->maternal_surname = null;
      $item->user->full_name = null;
      $item->user->curp = null;
      $item->user->email = null;
      $item->user->phone = null;
      $item->user->avatar = null;
      $item->user->uiid = null;
      $item->user->avatar_doc = null;
      $item->user->avatar_dlt = null;
    }

    return $item;
  }
}
