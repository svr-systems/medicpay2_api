<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder {
  public function run() {
    $now = Carbon::now()->format('Y-m-d H:i:s');

    $items = [
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ALERGOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ALERGOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ALGOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ANGIOLOGIA Y CIRUGIA VASCULAR'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'AUDIOLOGIA Y FONIATRIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CARDIOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CARDIOLOGIA INTERVENCIONISTA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA CARDIOTORACICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CARDIOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA CARDIOTORACICA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA DE COLUMNA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA GENERAL'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA LAPAROSCOPIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA ONCOLOGICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA ONCOLOGICA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA ORAL Y MAXILOFACIAL'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA PLASTICA Y RECONSTRUCTIVA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CIRUGIA VASCULAR'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'COLOPROCTOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'DERMATOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'DERMATOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ENDOCRINOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ENDOCRINOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ENDOSCOPIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ENDOSCOPIA DIGESTIVA Y RESPIRATORIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'GASTROENTEROLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'GASTROENTEROLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'GASTROENTEROLOGIA Y ENDOSCOPIA DIGESTIVA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'GERIATRIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'GINECOLOGIA ONCOLOGICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'GINECOLOGIA Y OBSTETRICIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'HEMATOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'IMAGENOLOGIA YRADIODIAGNOSTICO'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'INFECTOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'INFECTOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'INMUNOLOGIA Y ALERGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA CRITICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA DE LA ACTIVIDAD FISICA Y EL DEPORTE'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA DE REHABILITACION'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA DEL DEPORTE'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA DEL ENFERMO EN ESTADO CRITICO'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA DEL TRABAJO'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA FAMILIAR'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA FISICA Y REHABILITACION'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA INTERNA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA NUCLEAR'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA PALIATIVA Y DEL DOLOR'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA PREVENTIVA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NEFROLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NEFROLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NEONATOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NEUMOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NEUMOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NEUROCIRUGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NEUROCIRUGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NEUROLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NEUROLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NUTRIOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'NUTRIOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ODONTOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ODONTOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'OFTALMOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'OFTALMOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ONCOLOGIA MEDICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ONCOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ORTOPEDIA Y TRAUMATOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'OTORRINOLARINGOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'OTORRINOLARINGOLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'OTORRINOLARINGOLOGIA Y CIRUGIA DE CABEZA Y CUELLO'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'PODOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'PEDIATRIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'PROCTOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'PSIQUIATRIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'PSICOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'RADIOLOGIA E IMAGEN'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'REHABILITACION BUCAL'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'REHABILITACION'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'REUMATOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'TERAPIA DE LENGUAJE'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'TERAPIA FISICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'TRAUMATOLOGIA Y ORTOPEDIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'UROLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'UROLOGIA PEDIATRICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'MEDICINA GENERAL'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'TELEM PSICOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'CONSULTA TELEFÓNICA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ANESTESIOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ENFERMERIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'INSTRUMENTISTA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'TOXICOLOGIA'
      ],
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'PATOLOGIA'
      ]
    ];

    Specialty::insert($items);
  }
}
