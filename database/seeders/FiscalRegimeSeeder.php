<?php

namespace Database\Seeders;

use App\Models\FiscalRegime;
use Illuminate\Database\Seeder;

class FiscalRegimeSeeder extends Seeder {
  public function run() {
    $items = [
      [
        'name' => 'GENERAL DE LEY PERSONAS MORALES',
        'code' => '601',
      ],
      [
        'name' => 'PERSONAS MORALES CON FINES NO LUCRATIVOS',
        'code' => '603',
      ],
      [
        'name' => 'SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS',
        'code' => '605',
      ],
      [
        'name' => 'ARRENDAMIENTO',
        'code' => '606',
      ],
      [
        'name' => 'RÉGIMEN DE ENAJENACIÓN O ADQUISICIÓN DE BIENES',
        'code' => '607',
      ],
      [
        'name' => 'DEMÁS INGRESOS',
        'code' => '608',
      ],
      [
        'name' => 'CONSOLIDACIÓN',
        'code' => '609',
      ],
      [
        'name' => 'RESIDENTES EN EL EXTRANJERO SIN ESTABLECIMIENTO PERMANENTE EN MÉXICO',
        'code' => '610',
      ],
      [
        'name' => 'INGRESOS POR DIVIDENDOS (SOCIOS Y ACCIONISTAS)',
        'code' => '611',
      ],
      [
        'name' => 'PERSONAS FÍSICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES',
        'code' => '612',
      ],
      [
        'name' => 'INGRESOS POR INTERESES',
        'code' => '614',
      ],
      [
        'name' => 'RÉGIMEN DE LOS INGRESOS POR OBTENCIÓN DE PREMIOS',
        'code' => '615',
      ],
      [
        'name' => 'SIN OBLIGACIONES FISCALES',
        'code' => '616',
      ],
      [
        'name' => 'SOCIEDADES COOPERATIVAS DE PRODUCCIÓN QUE OPTAN POR DIFERIR SUS INGRESOS',
        'code' => '620',
      ],
      [
        'name' => 'INCORPORACIÓN FISCAL',
        'code' => '621',
      ],
      [
        'name' => 'ACTIVIDADES AGRÍCOLAS, GANADERAS, SILVÍCOLAS Y PESQUERAS',
        'code' => '622',
      ],
      [
        'name' => 'OPCIONAL PARA GRUPOS DE SOCIEDADES',
        'code' => '623',
      ],
      [
        'name' => 'COORDINADOS',
        'code' => '624',
      ],
      [
        'name' => 'RÉGIMEN DE LAS ACTIVIDADES EMPRESARIALES CON INGRESOS A TRAVÉS DE PLATAFORMAS TECNOLÓGICAS',
        'code' => '625',
      ],
      [
        'name' => 'RÉGIMEN SIMPLIFICADO DE CONFIANZA',
        'code' => '626',
      ],
      [
        'name' => 'HIDROCARBUROS',
        'code' => '628',
      ],
      [
        'name' => 'DE LOS REGÍMENES FISCALES PREFERENTES Y DE LAS EMPRESAS MULTINACIONALES',
        'code' => '629',
      ],
      [
        'name' => 'ENAJENACIÓN DE ACCIONES EN BOLSA DE VALORES',
        'code' => '630',
      ],
    ];

    FiscalRegime::insert($items);
  }
}
