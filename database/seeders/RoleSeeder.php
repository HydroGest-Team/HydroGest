<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_roles')->insert([
            ['nombre_rol' => 'Administrador', 'descripcion_rol' => 'Acceso total al sistema',      'created_at' => now(), 'updated_at' => now()],
            ['nombre_rol' => 'Secretaria',    'descripcion_rol' => 'Registro de clientes y pagos', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_rol' => 'Empleado',        'descripcion_rol' => 'Registro de lecturas de campo','created_at' => now(), 'updated_at' => now()],
        ]);
    }
}