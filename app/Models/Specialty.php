<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Specialty extends Model {
  use HasFactory;
  protected function serializeDate(DateTimeInterface $date) {
    return Carbon::instance($date)->toISOString(true);
  }
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  public static function valid($data) {
    $rules = [
      'name' => 'required|string|min:2|max:80',
      'is_doctor' => 'required|boolean'
    ];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }

  static public function getUiid($id) {
    return 'ES-' . str_pad($id, 3, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $items = Specialty::
    where('is_active', boolval($req->is_active));

    $items = $items->
      orderBy('name')->
      get([
        'id',
        'is_active',
        'name',
        'is_doctor'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->uiid = Specialty::getUiid($item->id);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = Specialty::find($id);
    $item->uiid = Specialty::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);

    return $item;
  }
}
