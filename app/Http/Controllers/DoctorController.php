<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSpecialty;
use App\Models\Hospital;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Throwable;

class DoctorController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => Doctor::getItems($req)]
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
        ['item' => Doctor::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = Doctor::find($id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $user = User::find($item->user_id);
      $user->is_active = false;
      $user->updated_by_id = $req->user()->id;
      $user->save();

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
      $item = Doctor::find($req->id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $user = User::find($item->user_id);
      $user->is_active = true;
      $user->updated_by_id = $req->user()->id;
      $user->save();

      DB::commit();
      return $this->apiRsp(
        200,
        'Registro activado correctamente',
        ['item' => Doctor::getItem(null, $item->id)]
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
      $req->user = json_encode($req->user);
      $user_data = json_decode($req->user);
      $user_data->role_id = 3;
      $email = GenController::filter($user_data->email, 'l');

      $valid = User::validEmail(['email' => $email], $user_data->id);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $valid = User::valid((array) $user_data);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $store_mode = is_null($id);

      if ($store_mode) {
        $user = new User;
        $user->created_by_id = $req->user()->id;
        $user->updated_by_id = $req->user()->id;

        $item = new Doctor;
      } else {
        $item = Doctor::find($id);
        $user = User::find($item->user_id);

        $user->updated_by_id = $req->user()->id;
      }

      $user = UserController::saveItem($user, $user_data);

      $req->hospital = json_encode($req->hospital);
      $hospital = json_decode($req->hospital);
      $hospital = Hospital::getItemBySubdomain($hospital->subdomain);

      $req->user_id = $user->id;
      $req->hospital_id = $hospital->id;

      $this->saveItem($item, $req);

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

  public static function saveItem($item, $data, $is_req = true) {
    $item->user_id = $data->user_id;
    $item->hospital_id = $data->hospital_id;
    $item->specialty_id = GenController::filter($data->specialty_id, 'id');
    $item->license = GenController::filter($data->license, 'U');

    $item->save();
    
  }

  public function valid(Request $req) {
    DB::beginTransaction();
    try {
      $valid = Doctor::validValidation($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }
      $item = Doctor::find($req->id);

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

  public function publicStore(Request $req) {
    DB::beginTransaction();
    try {
      $req->user = json_encode($req->user);
      $user_data = json_decode($req->user);
      $user_data->role_id = 3;
      $email = GenController::filter($user_data->email, 'l');

      $valid = User::validEmail(['email' => $email], $user_data->id);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $valid = User::valid((array) $user_data);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $user = new User;
      $item = new Doctor;

      $user = UserController::saveItem($user, $user_data);

      $req->hospital = json_encode($req->hospital);
      $hospital = json_decode($req->hospital);
      $hospital = Hospital::getItemBySubdomain($hospital->subdomain);

      $req->user_id = $user->id;
      $req->hospital_id = $hospital->id;

      $this->saveItem($item, $req);

      DB::commit();
      return $this->apiRsp(
        200,
        'Registro agregado correctamente',
        ['item' => ['id' => $item->id]]
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }
}
