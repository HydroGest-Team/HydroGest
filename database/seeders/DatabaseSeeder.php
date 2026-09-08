<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            TipoTarifaSeeder::class,
            TarifaSeeder::class,
            UserSeeder::class,
            ClienteSeeder::class,
            ContadorSeeder::class,
            PeriodoSeeder::class,
            LecturaSeeder::class,
            PagoSeeder::class,
        ]);
    }
}