<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function apiRsp($code, $msg, $data = null) {
    $ok = $code == 200 || $code == 201;
    $msg_err_def = 'Error. Contacte al equipo de soporte técnico';

    return response()->json([
      'msg' => is_null($msg) && !$ok ? $msg_err_def : $msg,
      'data' => !$ok && !is_null($data) ? "Message:\n" . $data->getMessage() . "\n\n" . "Trace:\n" . $data->getTraceAsString() : $data,
    ], $code);
  }
}
