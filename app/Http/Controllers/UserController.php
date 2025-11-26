<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Throwable;

class UserController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => User::getItems($req)]
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
        ['item' => User::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = User::find($id);

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
      $item = User::find($req->id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $user = User::find($item->id);
      $user->is_active = true;
      $user->updated_by_id = $req->user()->id;
      $user->save();

      DB::commit();
      return $this->apiRsp(
        200,
        'Registro activado correctamente',
        ['item' => User::getItem(null, $item->id)]
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
      $email_current = null;
      $email = GenController::filter($req->email, 'l');

      $valid = User::validEmail(['email' => $email], $id);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $valid = User::valid($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $store_mode = is_null($id);

      if ($store_mode) {
        $item = new User;
        $item->created_by_id = $req->user()->id;
        $item->updated_by_id = $req->user()->id;
      } else {
        $item = User::find($id);
        $email_current = $item->email;

        $item->updated_by_id = $req->user()->id;
      }

      $item = $this->saveItem($item, $req);

      if ($email_current != $email) {
        $item->email_verified_at = null;
        $item->save();

        EmailController::userAccountConfirmation($item->email, $item);
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

  public static function saveItem($item, $data, $is_req = true) {
    if (!$is_req) {
      $item->active = GenController::filter($data->active, 'b');
    }

    $item->role_id = GenController::filter($data->role_id, 'id');
    $item->name = GenController::filter($data->name, 'U');
    $item->paternal_surname = GenController::filter($data->paternal_surname, 'U');
    $item->maternal_surname = GenController::filter($data->maternal_surname, 'U');
    $item->curp = GenController::filter($data->curp, 'U');
    $item->email = GenController::filter($data->email, 'l');
    $item->phone = GenController::filter($data->phone, 'U');
    $item->avatar = DocMgrController::save(
      $data->avatar,
      DocMgrController::exist($data->avatar_doc),
      $data->avatar_dlt,
      'User'
    );
    $item->save();

    return $item;
  }

  public function getItemAccountConfirm($id) {
    try {
      $item = User::find(Crypt::decryptString($id));

      if (
        !$item ||
        !boolval($item->is_active) ||
        !is_null($item->email_verified_at)
      ) {
        return $this->apiRsp(422, 'La cuenta ya esta confirmada y/o la acción no es procesable');
      }

      return $this->apiRsp(
        200,
        'Registro retornado correctamente',
        ['item' => [
          'email' => $item->email,
          'role_id' => $item->role_id,
        ]]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function accountConfirm(Request $req, $id) {
    DB::beginTransaction();
    try {
      $valid = User::validPassword($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $item = User::find(Crypt::decryptString($id));

      if (!$item || !boolval($item->is_active)) {
        return $this->apiRsp(422, 'La acción no es procesable');
      }

      $item->email_verified_at = date('Y-m-d H:i:s');
      $item->password = bcrypt(GenController::trim($req->password));
      $item->save();

      EmailController::userAccountConfirm($item->email, $item);

      DB::commit();
      return $this->apiRsp(200, 'Cuenta confirmada correctamente');
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function getItemPasswordReset($id) {
    try {
      $item = User::find(Crypt::decryptString($id));

      if (
        !$item ||
        !boolval($item->is_active) ||
        is_null($item->password_recover_at) ||
        Carbon::createFromFormat('Y-m-d H:i:s', $item->password_recover_at)->addMinutes(5)->isPast()
      ) {
        return $this->apiRsp(422, 'Se excedierón los 5 min. para realizar esta acción o ya no es procesable');
      }

      return $this->apiRsp(
        200,
        'Registro retornado correctamente',
        ['item' => [
          'email' => $item->email,
          'role_id' => $item->role_id,
        ]]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function passwordReset(Request $req, $id) {
    DB::beginTransaction();
    try {
      $valid = User::validPassword($req->all());
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $item = User::find(Crypt::decryptString($id));

      if (!$item || !boolval($item->is_active)) {
        return $this->apiRsp(422, 'Acción no procesable');
      }

      $item->password = bcrypt(GenController::trim($req->password));
      $item->save();

      EmailController::userPasswordReset($item->email, $item);

      DB::commit();
      return $this->apiRsp(200, 'Contraseña restablecida correctamente');
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function passwordRecover(Request $req) {
    try {
      $item = User::getItemByEmail($req->email);

      $msg = null;

      if (!$item) {
        $msg = 'No tenemos ningún usuario registrado con este E-mail';
      } else {
        if (!$item->is_active) {
          $msg = 'Cuenta inactiva, no se puede enviar E-mail';
        } else {
          if (is_null($item->email_verified_at)) {
            $msg = 'Cuenta no confirmada, no se puede enviar E-mail';
          } else {
            if (
              $item->password_recover_at &&
              !Carbon::createFromFormat('Y-m-d H:i:s', $item->password_recover_at)->addMinutes(5)->isPast()
            ) {
              $msg = 'El E-mail de recuperación ha sido enviado, espera 5 min. para utilizar nuevamente esta acción';
            }
          }
        }
      }

      if ($msg) {
        return $this->apiRsp(422, $msg);
      }

      $item->password_recover_at = date('Y-m-d H:i:s');
      $item->save();

      EmailController::userPasswordRecover($item->email, $item);

      return $this->apiRsp(200, 'E-mail de recuperación enviado');
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
