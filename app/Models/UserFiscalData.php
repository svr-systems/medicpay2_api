<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class UserFiscalData extends Model
{
  use HasFactory;

  protected $table = 'user_fiscal_data';

  protected function serializeDate(DateTimeInterface $date): string
  {
    return $date->format('Y-m-d H:i:s');
  }

  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  public static function valid($data)
  {
    $rules = [
      'code' => 'required|string|min:2|max:13',
      'name' => 'required|string|min:2|max:75',
      'zip' => 'required|string|min:2|max:5',
      'fiscal_regime_id' => 'required|numeric',
    ];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }

  static public function getUiid($id)
  {
    return 'DF-' . str_pad($id, 3, '0', STR_PAD_LEFT);
  }

  static public function getItem($user_id)
  {
    $item = UserFiscalData::query()
      ->where('user_id', $user_id)
      ->first([
        'id',
        'code',
        'name',
        'zip',
        'fiscal_regime_id'
      ]);

    if ($item) {
      $item->uiid = UserFiscalData::getUiid($item->id);
      $item->fiscal_regime = FiscalRegime::find($item->fiscal_regime_id, ['id', 'name', 'code']);
    } else {
      $item = new \stdClass;

      $item->id = '';
      $item->is_active = true;
      $item->code = '';
      $item->name = '';
      $item->zip = '';
      $item->uiid = '';
      $item->fiscal_regime_id = '';
      $item->fiscal_regime = new \stdClass;
      $item->fiscal_regime->id = '';
      $item->fiscal_regime->name = '';
      $item->fiscal_regime->code = '';
    }

    return $item;
  }

  // static public function getItem($req, $id) {
  //   $item = UserFiscalData::find($id);

  //   $item->uiid = UserFiscalData::getUiid($item->id);
  //   $item->created_by = User::find($item->created_by_id, ['email']);
  //   $item->updated_by = User::find($item->updated_by_id, ['email']);
  //   $item->fiscal_regime = FiscalRegime::find($item->fiscal_regime_id);

  //   return $item;
  // }

  static public function getDataByUser($user_id)
  {
    $item = UserFiscalData::where("user_id", $user_id)->
      first();

    $item->uiid = UserFiscalData::getUiid($item->id);
    $item->fiscal_regime = FiscalRegime::find($item->fiscal_regime_id);

    return $item;
  }
}
