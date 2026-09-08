<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoTarifaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_tipo_tarifa')->insert([
            ['nombre_tipo' => 'Residencial', 'descripcion' => 'Tarifa para uso doméstico', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}