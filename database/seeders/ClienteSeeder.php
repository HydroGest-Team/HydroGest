<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_clientes')->insert([
            [
                'dpi_cliente'       => '1234567890101',
                'nombre1_cliente'   => 'Carlos',
                'nombre2_cliente'   => 'Antonio',
                'apellido1_cliente' => 'García',
                'apellido2_cliente' => 'López',
                'telefono_cliente'  => '55551001',
                'direccion_cliente' => 'Zona 1, Calle Principal 1-10',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'dpi_cliente'       => '2345678901202',
                'nombre1_cliente'   => 'María',
                'nombre2_cliente'   => 'Elena',
                'apellido1_cliente' => 'Pérez',
                'apellido2_cliente' => 'Ruiz',
                'telefono_cliente'  => '55551002',
                'direccion_cliente' => 'Zona 2, Avenida Central 2-20',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'dpi_cliente'       => '3456789012303',
                'nombre1_cliente'   => 'José',
                'nombre2_cliente'   => 'Luis',
                'apellido1_cliente' => 'Martínez',
                'apellido2_cliente' => 'Gómez',
                'telefono_cliente'  => '55551003',
                'direccion_cliente' => 'Zona 3, Calle Secundaria 3-30',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'dpi_cliente'       => '4567890123404',
                'nombre1_cliente'   => 'Ana',
                'nombre2_cliente'   => 'Lucía',
                'apellido1_cliente' => 'Hernández',
                'apellido2_cliente' => 'Castro',
                'telefono_cliente'  => '55551004',
                'direccion_cliente' => 'Zona 4, Boulevard Norte 4-40',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'dpi_cliente'       => '5678901234505',
                'nombre1_cliente'   => 'Pedro',
                'nombre2_cliente'   => 'Raúl',
                'apellido1_cliente' => 'Flores',
                'apellido2_cliente' => 'Vásquez',
                'telefono_cliente'  => '55551005',
                'direccion_cliente' => 'Zona 5, Callejón del Río 5-50',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}