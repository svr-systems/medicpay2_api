<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\UserFiscalData;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Throwable;

class UserFiscalDataController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['item' => UserFiscalData::getItem($req->user()->id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function show(Request $req, $id) {
    try {
      return $this->apiRsp(
        200,
        'Registro retornado correctamente',
        ['item' => UserFiscalData::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = UserFiscalData::find($id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $item->is_active = false;
      $item->updated_by_id = $req->user()->id;
      $item->save();

      DB::commit();
      return $this->apiRsp(
        200,
        'Registro inactivado correctamente'
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }

  public function restore(Request $req) {
    DB::beginTransaction();
    try {
      $item = UserFiscalData::find($req->id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $item->is_active = true;
      $item->updated_by_id = $req->user()->id;
      $item->save();

      DB::commit();
      return $this->apiRsp(
        200,
        'Registro activado correctamente',
        ['item' => UserFiscalData::getItem(null, $item->id)]
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }

  public function store(Request $req) {
    return $this->storeUpdate($req, $req->id);
  }

  public function update(Request $req, $id) {
    return $this->storeUpdate($req, $id);
  }

  public function storeUpdate($req, $id) {
    DB::beginTransaction();
    try {
      $valid = UserFiscalData::valid($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }
      
      $valid = FacturapiDataController::validCustomer($req);
      if ($valid->msg !== null) {
        return $this->apiRsp(422, $valid->msg);
      }

      $req->user_id = $req->user()->id;

      $store_mode = is_null($id);

      if ($store_mode) {
        $item = new UserFiscalData;
        $item->created_by_id = $req->user()->id;
        $item->updated_by_id = $req->user()->id;
      } else {
        $item = UserFiscalData::find($id);
        $item->updated_by_id = $req->user()->id;
      }

      $item = $this->saveItem($item, $req);

      DB::commit();
      return $this->apiRsp(
        $store_mode ? 201 : 200,
        'Registro ' . ($store_mode ? 'agregado' : 'editado') . ' correctamente',
        $store_mode ? ['item' => ['id' => $item->id]] : null
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }

  public static function saveItem($item, $data) {
    $item->user_id = $data->user_id;
    $item->code = GenController::filter($data->code, 'U');
    $item->name = GenController::filter($data->name, 'U');
    $item->zip = GenController::filter($data->zip, 'U');
    $item->fiscal_regime_id = GenController::filter($data->fiscal_regime_id, 'id');
    $item->save();

    return $item;
  }


  // PUBLIC
  public function getFiscalDataByConsultation($consultation_id) {
    try {
      $consultation_id = Crypt::decryptString($consultation_id);
      $consultation = Consultation::getGeneral($consultation_id);
      $data = UserFiscalData::getItem($consultation->patient->user->id);
      $consultation = Consultation::getItemById($consultation_id);
      $doctor = Doctor::getItem(null,$consultation->doctor_id);
      $data->consultation = Consultation::getEmailData($consultation,$doctor);
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['item' => $data]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function setFiscalDataByConsultation(Request $req, $consultation_id) {
    DB::beginTransaction();
    try {
      $consultation_id = Crypt::decryptString($consultation_id);
      $valid = UserFiscalData::valid($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }
      
      $valid = FacturapiDataController::validCustomer($req);
      if ($valid->msg !== null) {
        return $this->apiRsp(422, $valid->msg);
      }

      $consultation = Consultation::getGeneral($consultation_id);
      $user_id = $consultation->patient->user->id;
      $user_fiscal_data = UserFiscalData::getItem($user_id);
      $req->user_id = $user_id;
      
      $id = GenController::filter($user_fiscal_data->id,'id');

      $store_mode = is_null($id);

      if ($store_mode) {
        $item = new UserFiscalData;
      } else {
        $item = UserFiscalData::find($id);
      }

      $item = $this->saveItem($item, $req);

      DB::commit();
      return $this->apiRsp(
        $store_mode ? 201 : 200,
        'Registro ' . ($store_mode ? 'agregado' : 'editado') . ' correctamente',
        $store_mode ? ['item' => ['id' => $item->id]] : null
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }
}
