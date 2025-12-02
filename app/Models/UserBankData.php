<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class UserBankData extends Model {
  use HasFactory;
  protected $table = 'user_bank_data';
  protected function serializeDate(DateTimeInterface $date) {
    return Carbon::instance($date)->toISOString(true);
  }
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  public static function valid($data) {
    $rules = [
      'bank_type_id' => 'required|numeric',
      'bank_account' => 'required|string|min:2|max:15',
      'bank_clabe' => 'required|string|min:2|max:18',
    ];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
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

  static public function getUiid($id) {
    return 'UBD-' . str_pad($id, 3, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $item = UserBankData::
    where('user_id', $req->user()->id);

    $item = $item->
      first([
        'id',
        'is_active',
        'user_id',
        'bank_type_id',
        'bank_account',
        'bank_clabe'
      ]);

      if($item){
        $item->uiid = UserBankData::getUiid($item->id);
        $item->bank_type = BankType::find($item->bank_type_id,['id','name']);
      }else{
        $item = new \stdClass;
        $item->id = "";
        $item->is_active = true;
        $item->bank_type_id = "";
        $item->bank_account = "";
        $item->bank_clabe = "";
        $item->bank_type = new \stdClass();
        $item->bank_type->id = "";
        $item->bank_type->name = "";
      }

    return $item;
  }

  static public function getItem($req, $id) {
    $item = UserBankData::find($id);

    $item->uiid = UserBankData::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->bank_type = BankType::find($item->bank_type_id);

    return $item;
  }
}
