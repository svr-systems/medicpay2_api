<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Throwable;

class CatalogController extends Controller {
  public function index(Request $req, $catalog) {
    try {
      $model = match ($catalog) {
        'states' => \App\Models\State::class,
        'municipalities' => \App\Models\Municipality::class,
        'roles' => \App\Models\Role::class,
        'fiscal_regimes' => \App\Models\FiscalRegime::class,
        'bank_types' => \App\Models\BankType::class,
        default => null,
      };

      abort_if(!$model, 404, 'Catálogo no encontrado');
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => $model::getItems($req)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
  
  public function public(Request $req, $catalog) {
    try {
      $model = match ($catalog) {
        'fiscal_regimes' => \App\Models\FiscalRegime::class,
        default => null,
      };

      abort_if(!$model, 404, 'Catálogo no encontrado');
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => $model::getItems($req)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
