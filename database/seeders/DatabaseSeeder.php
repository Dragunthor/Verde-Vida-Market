<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategoriasSeeder::class,
            UsuariosSeeder::class,
            ProductosSeeder::class,
            PedidosSeeder::class,
            ReportesSeeder::class,
            ResenasSeeder::class,
        ]);
    }
}