<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_usuarios')->insert([
            [
                'name'       => 'Administrador',
                'email'      => 'admin@hidrogest.test',
                'password'   => Hash::make('password'),
                'role_id'    => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Secretaria',
                'email'      => 'secretaria@hidrogest.test',
                'password'   => Hash::make('password'),
                'role_id'    => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Lector',
                'email'      => 'lector@hidrogest.test',
                'password'   => Hash::make('password'),
                'role_id'    => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
