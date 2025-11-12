<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use stdClass;

class DocMgrController extends Controller {
  public static function save($val, $doc, $dlt, $fld) {
    if (!GenController::empty($doc)) {
      $name = Str::random(43) . '.' . $doc->getClientOriginalExtension();
      Storage::disk($fld)->put($name, file_get_contents($doc));

      if (!GenController::empty($val)) {
        Storage::disk($fld)->delete($val);
      }

      return $name;
    } else {
      if (GenController::filter($dlt, 'b')) {
        Storage::disk($fld)->delete($val);

        return null;
      }
    }

    return GenController::empty($val) ? null : $val;
  }

  public static function getB64($val, $fld) {
    if (!empty($val)) {
      $path = Storage::disk($fld)->path($val);

      if (file_exists($path)) {
        $b64 = new stdClass;
        $b64->cnt = base64_encode(file_get_contents($path));
        $b64->ext = pathinfo($path, PATHINFO_EXTENSION);

        return $b64;
      }
    }

    return null;
  }

  public static function exist($path) {
    return is_file($path) ? $path : null;
  }
}
