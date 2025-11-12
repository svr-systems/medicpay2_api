<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller {
  public function login(Request $req) {
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

      return $this->apiRsp(
        200,
        'Datos de acceso validos',
        [
          'auth' => [
            'token' => Auth::user()->createToken('passportToken')->accessToken,
            'user' => User::find(Auth::id())
          ]
        ]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function logout(Request $req) {
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
