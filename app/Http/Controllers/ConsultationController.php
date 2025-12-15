<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\User;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Throwable;

class ConsultationController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => Consultation::getItems($req)]
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
        ['item' => Consultation::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = Consultation::find($id);

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
      $item = Consultation::find($req->id);

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
        ['item' => Consultation::getItem($req, $item->id)]
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }

  public function store(Request $req) {
    return $this->storeUpdate($req, null);
  }

  public function update(Request $req, $id) {
    return $this->storeUpdate($req, $id);
  }

  public function storeUpdate($req, $id) {
    DB::beginTransaction();
    try {
      $valid = Consultation::valid($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $patient = json_encode($req->patient);
      $patient = json_decode($patient);
      $patient = json_decode($req->patient);
      $user = json_encode($patient->user);
      $patient_id = $patient->id;
      $req->user = $user;
      $patient_controller = new PatientController;
      $patient = $patient_controller->saveItem($req, $patient_id);
      if (is_array($patient)) {
        return $this->apiRsp(422, $patient['msg']);
      }

      $req->patient_id = $patient->id;

      $store_mode = is_null($id);

      if ($store_mode) {
        $item = new Consultation;
        $item->created_by_id = $req->user()->id;
        $item->updated_by_id = $req->user()->id;
      } else {
        $item = Consultation::find($id);
        $item->updated_by_id = $req->user()->id;
      }

      $item = $this->saveItem($item, $req);

      DB::commit();

      $email_data = Consultation::getItemEmail($req, $item->id);

      $pdf = new PdfController;
      $file_path = $pdf->consultation($email_data);
      EmailController::sendConsultation(null, $email_data, $file_path);
      \Storage::delete($file_path);

      // return $email_data;
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
    $doctor = Doctor::getItemByUserId($data->user()->id);

    $item->doctor_id = $doctor->id;
    $item->patient_id = GenController::filter($data->patient_id, 'id');
    $item->consultation_amount = GenController::filter($data->consultation_amount, 'd');
    $item->charge_amount = ConsultationController::getCalcChargeAmount($item->consultation_amount, $doctor->hospital_id);
    $item->save();

    return $item;
  }

  public function getInfo(Request $req) {
    try {
      $folio = $req->folio;
      if(GenController::filter($req->encripted,'b')){
        $folio = Crypt::decryptString($folio);
      }

      return $this->apiRsp(
        200,
        'Registro retornado correctamente',
        ['item' => Consultation::getInfoByFolio($folio)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  static public function getCalcChargeAmount($consultation_amount, $hospital_id) {
    $fee = (int) Hospital::find($hospital_id)->fee;
    return ceil(($consultation_amount * (1 + $fee / 100)) / 100) * 100;
  }

  static public function getConsultationFolio($data) {
    return date("ymdHis", strtotime($data->created_at)) .
      str_pad($data->patient->id, 4, '0', STR_PAD_LEFT);
  }
}
