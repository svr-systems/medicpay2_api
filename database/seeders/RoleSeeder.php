<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {
  public function run() {
    $items = [
      [
        'name' => 'ADMINISTRADOR'
      ],
      [
        'name' => 'USUARIO'
      ],
      [
        'name' => 'MÉDICO'
      ],
      [
        'name' => 'PACIENTE'
      ],
    ];

    Role::insert($items);
  }
}
