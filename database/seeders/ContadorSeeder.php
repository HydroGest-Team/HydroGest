<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContadorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_contadores')->insert([
            ['codigo_contador' => 'CTR-001', 'sector_contador' => 'Sector A', 'fecha_instalacion' => '2024-01-15', 'activo_contador' => 'ACTIVO', 'cliente_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['codigo_contador' => 'CTR-002', 'sector_contador' => 'Sector A', 'fecha_instalacion' => '2024-01-15', 'activo_contador' => 'ACTIVO', 'cliente_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['codigo_contador' => 'CTR-003', 'sector_contador' => 'Sector B', 'fecha_instalacion' => '2024-03-10', 'activo_contador' => 'ACTIVO', 'cliente_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['codigo_contador' => 'CTR-004', 'sector_contador' => 'Sector B', 'fecha_instalacion' => '2024-03-10', 'activo_contador' => 'ACTIVO', 'cliente_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['codigo_contador' => 'CTR-005', 'sector_contador' => 'Sector C', 'fecha_instalacion' => '2024-06-20', 'activo_contador' => 'ACTIVO', 'cliente_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}