<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use App\Models\UserBankData;
use DB;
use Illuminate\Http\Request;
use Throwable;

class UserBankDataController extends Controller
{
  public function index(Request $req)
  {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['item' => UserBankData::getItems($req)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function show(Request $req, $id)
  {
    try {
      return $this->apiRsp(
        200,
        'Registro retornado correctamente',
        ['item' => UserBankData::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function store(Request $req)
  {
    return $this->storeUpdate($req, $req->id);
  }

  public function update(Request $req, $id)
  {
    return $this->storeUpdate($req, $id);
  }

  public function storeUpdate($req, $id)
  {
    DB::beginTransaction();
    try {
      $valid = UserBankData::valid($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $store_mode = is_null($id);

      if ($store_mode) {
        $item = new UserBankData;
        $item->created_by_id = $req->user()->id;
        $item->updated_by_id = $req->user()->id;
      } else {
        $item = UserBankData::find($id);
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

  public static function saveItem($item, $data)
  {
    $item->user_id = $data->user()->id;
    $item->bank_type_id = GenController::filter($data->bank_type_id, 'id');
    $item->bank_account = GenController::filter($data->bank_account, 'U');
    $item->bank_clabe = GenController::filter($data->bank_clabe, 'U');
    $item->save();

    return $item;
  }

  public function valid(Request $req)
  {
    DB::beginTransaction();
    try {
      $valid = UserBankData::validValidation($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }
      $item = UserBankData::find($req->id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $item->updated_by_id = $req->user()->id;
      $item->is_valid = GenController::filter($req->is_valid, 'b');
      $item->validated_by_id = $req->user()->id;
      $item->validated_at = date('Y-m-d H:i:s');
      $item->bank_validated_path = DocMgrController::save(
        $req->bank_validated_path,
        DocMgrController::exist($req->license_doc),
        $req->license_dlt,
        'UserBankData'
      );
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

  public function clabeValid(Request $req)
  {
    try {
      $clabe = str_replace(' ', '', $req->bank_clabe);

      $bank_code = substr($clabe, 0, 3);
      $bank_type = BankType::getByCode($bank_code);

      $item = new \stdClass;
      $item->bank_type_id = $bank_type->id;
      $item->bank_account = substr($clabe, 6, 11);
      $item->bank_clabe = $clabe;
      $item->bank_type = $bank_type;

      return $this->apiRsp(
        200,
        'Registro retornado correctamente',
        ['item' => $item]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
