<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogController extends Controller {
  public function index(Request $req, $catalog) {
    $model = match ($catalog) {
      'states' => \App\Models\State::class,
      'municipalities' => \App\Models\Municipality::class,
      'roles' => \App\Models\Role::class,
      default => null,
    };

    abort_if(!$model, 404, 'Catálogo no encontrado');

    return $model::getItems($req);
  }
}
