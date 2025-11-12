<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model {
  use HasFactory;
  public $timestamps = false;
  static public function getItems($req) {
    $items = Role::
      orderBy('name')->
      where('is_active', true)->
      where('id', '<', 3);

    $items = $items->get([
      'id',
      'name',
    ]);

    return $items;
  }
}
