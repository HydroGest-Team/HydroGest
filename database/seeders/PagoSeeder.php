<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_pagos')->insert([
            [
                'lecturas_id'  => 1,
                'monto_pago'   => 300.00,
                'fecha_pago'   => '2026-07-10',
                'metodo_pago'  => 'Efectivo',
                'estado_pago'  => 'PAGADO',
                'usuario_id'   => 2,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'lecturas_id'  => 6,
                'monto_pago'   => 375.00,
                'fecha_pago'   => '2026-08-12',
                'metodo_pago'  => 'Efectivo',
                'estado_pago'  => 'PAGADO',
                'usuario_id'   => 2,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}
