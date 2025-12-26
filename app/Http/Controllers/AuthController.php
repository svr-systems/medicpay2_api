<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Role;
use App\Models\UserBankData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller
{
  public function login(Request $req)
  {
    try {
      $email = GenController::filter($req->email, 'l');

      $user = User::
        where('email', $email)->
        first();

      if (
        !Auth::attempt([
          'email' => $email,
          'password' => trim($req->password)
        ])
      ) {
        return $this->apiRsp(422, 'Datos de acceso inválidos', null);
      }

      $user = User::find(Auth::id());
      $user->role = Role::find($user->role_id, ['name']);

      return $this->apiRsp(
        200,
        'Datos de acceso validos',
        [
          'auth' => [
            'token' => Auth::user()->createToken('passportToken')->accessToken,
            'user' => $user
          ]
        ]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function logout(Request $req)
  {
    try {
      $req->user()->token()->revoke();

      return $this->apiRsp(
        200,
        'Sesión finalizada correctamente'
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
