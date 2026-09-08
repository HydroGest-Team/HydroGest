<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_periodos')->insert([
            [
                'fecha_apertura' => '2026-07-01 00:00:00',
                'fecha_cierre'   => '2026-07-31 23:59:59',
                'estado_periodo' => 'CERRADO',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'fecha_apertura' => '2026-08-01 00:00:00',
                'fecha_cierre'   => '2026-08-31 23:59:59',
                'estado_periodo' => 'CERRADO',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'fecha_apertura' => '2026-09-01 00:00:00',
                'fecha_cierre'   => null,
                'estado_periodo' => 'ACTIVO',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}