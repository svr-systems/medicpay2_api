<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturapiData extends Model {
  use HasFactory;

  static public function getItemByUserFiscalData($user_fiscal_data_id) {
    $items = FacturapiData::
      where('user_fiscal_data_id', $user_fiscal_data_id)->
      where('is_active', true)->
      first();

    return $items;
  }
}
