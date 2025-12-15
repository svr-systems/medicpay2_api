<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use App\Models\Consultation;
use App\Models\Transaction;
use DB;
use Illuminate\Http\Request;
use Throwable;

class TransactionController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => Transaction::getItems($req)]
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
        ['item' => Transaction::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = Transaction::find($id);

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
      $item = Transaction::find($req->id);

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
        ['item' => Transaction::getItem(null, $item->id)]
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
      $valid = Transaction::valid($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $store_mode = is_null($id);

      if ($store_mode) {
        $item = new Transaction;
      } else {
        $item = Transaction::find($id);
      }
      
      $bank_type = BankType::getByCode($req->bank_code);
      $payment_form_id = ($req->card_product === 'c')?4:18;
      $req->bank_type_id = $bank_type->id;
      $req->payment_form_id = $payment_form_id;

      $item = $this->saveItem($item, $req);

      if($req->status){
        $consultation = Consultation::find($req->consultation_id);
        $consultation->transaction_id = $item->id;
        $consultation->save();
      }

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
    $item->consultation_id = GenController::filter($data->consultation_id, 'id');
    $item->status = GenController::filter($data->status, 'b');
    $item->card_number = GenController::filter($data->card_number, 'U');
    $item->bank_type_id = GenController::filter($data->bank_type_id, 'id');
    $item->payment_form_id = GenController::filter($data->payment_form_id, 'id');
    $item->authorization_code = GenController::filter($data->authorization_code, 'U');
    $item->reading_mode = GenController::filter($data->reading_mode, 'U');
    $item->arqc = GenController::filter($data->arqc, 'U');
    $item->aid = GenController::filter($data->aid, 'U');
    $item->financial_reference = GenController::filter($data->financial_reference, 'U');
    $item->terminal_number = GenController::filter($data->terminal_number, 'U');
    $item->transaction_sequence = GenController::filter($data->transaction_sequence, 'U');
    $item->cardholder_name = GenController::filter($data->cardholder_name, 'U');
    $item->error_message = GenController::filter($data->error_message, 'U');
    $item->response_code = GenController::filter($data->response_code, 'U');
    $item->is_points_used = GenController::filter($data->is_points_used, 'b');
    $item->points_redeemed = GenController::filter($data->points_redeemed, 'd');
    $item->amount_redeemed = GenController::filter($data->amount_redeemed, 'd');
    $item->previous_balance_amount = GenController::filter($data->previous_balance_amount, 'd');
    $item->previous_balance_points = GenController::filter($data->previous_balance_points, 'd');
    $item->current_balance_amount = GenController::filter($data->current_balance_amount, 'd');
    $item->current_balance_points = GenController::filter($data->current_balance_points, 'd');
    $item->operation_date = GenController::filter($data->operation_date, 'U');
    $item->payment_id = GenController::filter($data->payment_id, 'id');
    $item->save();

    if($item->status){
      FacturapiController::doctorConsultationStamp($item->consultation_id);
    }
    
    return $item;
  }
}
