<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_tarifas')->insert([
            [
                'tipo_tarifa_id'   => 1,
                'monto_por_unidad' => 2.50,
                'vigente_desde'    => '2026-01-01',
                'vigente_hasta'    => '2026-06-30',
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'tipo_tarifa_id'   => 1,
                'monto_por_unidad' => 3.00,
                'vigente_desde'    => '2026-07-01',
                'vigente_hasta'    => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}