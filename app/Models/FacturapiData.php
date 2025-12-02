<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturapiData extends Model {
  use HasFactory;

  static public function getUiid($id) {
    return 'DF-' . str_pad($id, 3, '0', STR_PAD_LEFT);
  }

  static public function getItem($req) {
    $user_fiscal_data = UserFiscalData::where('user_id',$req->user()->id)->
      first();
    $item = FacturapiData::
      where('user_fiscal_data_id', $user_fiscal_data->id)->
      first(['id','is_active','certificate_updated_at','certificate_expires_at','certificate_serial_number']);

    if(!$item){
      $item = new \stdClass;

      $item->id = "";
      $item->uiid = "SIN REGISTRO";
      $item->is_active = true;
      $item->certificate_updated_at = "";
      $item->certificate_expires_at = "";
      $item->certificate_serial_number = "";

    }else{
      $item->uiid = FacturapiData::getUiid($item->id);
    }

    return $item;
  }

  static public function getItemByUserFiscalData($user_fiscal_data_id) {
    $items = FacturapiData::
      where('user_fiscal_data_id', $user_fiscal_data_id)->
      where('is_active', true)->
      first();

    return $items;
  }
}
