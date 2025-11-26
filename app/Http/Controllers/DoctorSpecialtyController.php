<?php

namespace App\Http\Controllers;

use App\Models\DoctorSpecialty;
use DB;
use Illuminate\Http\Request;
use Throwable;

class DoctorSpecialtyController extends Controller
{

  public function valid(Request $req) {
    DB::beginTransaction();
    try {
      $valid = DoctorSpecialty::validValidation($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }
      $item = DoctorSpecialty::find($req->id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $item->is_valid = GenController::filter($req->is_valid,'b');
      $item->validated_by_id = $req->user()->id;
      $item->validated_at = date('Y-m-d H:i:s');
      $item->save();

      DB::commit();
      return $this->apiRsp(
        200,
        'Validación correctamente'
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }
}
