<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class UserFiscalData extends Model {
  use HasFactory;
  protected $table = 'user_fiscal_data';
  protected function serializeDate(DateTimeInterface $date) {
    return Carbon::instance($date)->toISOString(true);
  }
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  public static function valid($data) {
    $rules = [
      'user_id' => 'required|numeric',
      'code' => 'required|string|min:2|max:13',
      'name' => 'required|string|min:2|max:75',
      'zip' => 'required|string|min:2|max:5',
      'fiscal_regime_id' => 'required|numeric',
    ];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }

  static public function getUiid($id) {
    return 'UFD-' . str_pad($id, 3, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $items = UserFiscalData::
      where('is_active', boolval($req->is_active));

    $items = $items->
      orderBy('name')->
      get([
        'id',
        'is_active',
        'user_id',
        'code',
        'name',
        'zip',
        'fiscal_regime_id'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->uiid = UserFiscalData::getUiid($item->id);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = UserFiscalData::find($id);

    $item->uiid = UserFiscalData::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->fiscal_regime = FiscalRegime::find($item->fiscal_regime_id);

    return $item;
  }

  static public function getDataByUser($user_id) {
    $item = UserFiscalData::where("user_id",$user_id)->
      where('is_active',true)->
      first();

    $item->uiid = UserFiscalData::getUiid($item->id);
    $item->fiscal_regime = FiscalRegime::find($item->fiscal_regime_id);

    return $item;
  }
}
