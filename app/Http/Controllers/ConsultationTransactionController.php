<?php

namespace App\Http\Controllers;

use App\Models\ConsultationTransaction;
use DB;
use Illuminate\Http\Request;
use Throwable;

class ConsultationTransactionController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => ConsultationTransaction::getItems($req)]
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
        ['item' => ConsultationTransaction::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = ConsultationTransaction::find($id);

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
      $item = ConsultationTransaction::find($req->id);

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
        ['item' => ConsultationTransaction::getItem(null, $item->id)]
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
      $valid = ConsultationTransaction::valid($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $store_mode = is_null($id);

      if ($store_mode) {
        $item = new ConsultationTransaction;
        // $item->created_by_id = $req->user()->id;
        // $item->updated_by_id = $req->user()->id;
      } else {
        $item = ConsultationTransaction::find($id);
        // $item->updated_by_id = $req->user()->id;
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
    $item->consultation_id = GenController::filter($data->consultation_id, 'i');
    $item->status = GenController::filter($data->status, 'U');
    $item->merchant = GenController::filter($data->merchant, 'U');
    $item->affiliation = GenController::filter($data->affiliation, 'U');
    $item->transaction_type = GenController::filter($data->transaction_type, 'U');
    $item->card_number = GenController::filter($data->card_number, 'U');
    $item->bank_code = GenController::filter($data->bank_code, 'U');
    $item->card_product = GenController::filter($data->card_product, 'U');
    $item->authorization_code = GenController::filter($data->authorization_code, 'U');
    $item->reading_mode = GenController::filter($data->reading_mode, 'U');
    $item->arqc = GenController::filter($data->arqc, 'U');
    $item->aid = GenController::filter($data->aid, 'U');
    $item->financial_reference = GenController::filter($data->financial_reference, 'U');
    $item->terminal_number = GenController::filter($data->terminal_number, 'U');
    $item->transaction_sequence = GenController::filter($data->transaction_sequence, 'U');
    $item->cardholder_name = GenController::filter($data->cardholder_name, 'U');
    $item->legend = GenController::filter($data->legend, 'U');
    $item->response_code = GenController::filter($data->response_code, 'U');
    $item->is_points_used = GenController::filter($data->is_points_used, 'b');
    $item->points_redeemed = GenController::filter($data->points_redeemed, 'd');
    $item->amount_redeemed = GenController::filter($data->amount_redeemed, 'd');
    $item->previous_balance_amount = GenController::filter($data->previous_balance_amount, 'd');
    $item->previous_balance_points = GenController::filter($data->previous_balance_points, 'd');
    $item->current_balance_amount = GenController::filter($data->current_balance_amount, 'd');
    $item->current_balance_points = GenController::filter($data->current_balance_points, 'd');
    $item->is_credit = GenController::filter($data->is_credit, 'b');
    $item->save();

    return $item;
  }
}
