<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LecturaSeeder extends Seeder
{
    public function run(): void
    {
        // ── Período 1 (julio 2026) ──
        DB::table('tb_lecturas')->insert([
            ['numero_recibo' => 'REC-000001', 'contador_id' => 1, 'periodo_id' => 1, 'lectura_anterior' => 0.00,   'lectura_actual' => 120.00, 'tarifa_id' => 1, 'monto' => 300.00,  'fecha_lectura' => '2026-07-05', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['numero_recibo' => 'REC-000002', 'contador_id' => 2, 'periodo_id' => 1, 'lectura_anterior' => 0.00,   'lectura_actual' => 95.00,  'tarifa_id' => 1, 'monto' => 237.50, 'fecha_lectura' => '2026-07-05', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['numero_recibo' => 'REC-000003', 'contador_id' => 3, 'periodo_id' => 1, 'lectura_anterior' => 0.00,   'lectura_actual' => 210.00, 'tarifa_id' => 1, 'monto' => 525.00, 'fecha_lectura' => '2026-07-06', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['numero_recibo' => 'REC-000004', 'contador_id' => 4, 'periodo_id' => 1, 'lectura_anterior' => 0.00,   'lectura_actual' => 80.00,  'tarifa_id' => 1, 'monto' => 200.00, 'fecha_lectura' => '2026-07-06', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['numero_recibo' => 'REC-000005', 'contador_id' => 5, 'periodo_id' => 1, 'lectura_anterior' => 0.00,   'lectura_actual' => 155.00, 'tarifa_id' => 1, 'monto' => 387.50, 'fecha_lectura' => '2026-07-07', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── Período 2 (agosto 2026) ──
        DB::table('tb_lecturas')->insert([
            ['numero_recibo' => 'REC-000006', 'contador_id' => 1, 'periodo_id' => 2, 'lectura_anterior' => 120.00, 'lectura_actual' => 245.00, 'tarifa_id' => 2, 'monto' => 375.00, 'fecha_lectura' => '2026-08-05', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['numero_recibo' => 'REC-000007', 'contador_id' => 2, 'periodo_id' => 2, 'lectura_anterior' => 95.00,  'lectura_actual' => 198.00, 'tarifa_id' => 2, 'monto' => 309.00, 'fecha_lectura' => '2026-08-05', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['numero_recibo' => 'REC-000008', 'contador_id' => 3, 'periodo_id' => 2, 'lectura_anterior' => 210.00, 'lectura_actual' => 340.00, 'tarifa_id' => 2, 'monto' => 390.00, 'fecha_lectura' => '2026-08-06', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['numero_recibo' => 'REC-000009', 'contador_id' => 4, 'periodo_id' => 2, 'lectura_anterior' => 80.00,  'lectura_actual' => 162.00, 'tarifa_id' => 2, 'monto' => 246.00, 'fecha_lectura' => '2026-08-06', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['numero_recibo' => 'REC-000010', 'contador_id' => 5, 'periodo_id' => 2, 'lectura_anterior' => 155.00, 'lectura_actual' => 290.00, 'tarifa_id' => 2, 'monto' => 405.00, 'fecha_lectura' => '2026-08-07', 'usuario_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}