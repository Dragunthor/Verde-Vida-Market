<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            ['nombre' => 'Frutas Frescas', 'descripcion' => 'Frutas de temporada recién cosechadas'],
            ['nombre' => 'Verduras Orgánicas', 'descripcion' => 'Verduras cultivadas sin pesticidas'],
            ['nombre' => 'Lácteos y Huevos', 'descripcion' => 'Productos lácteos y huevos frescos'],
            ['nombre' => 'Panadería y Granos', 'descripcion' => 'Panes artesanales y granos enteros'],
            ['nombre' => 'Productos Lácteos Alternativos', 'descripcion' => 'Leches y yogures vegetales'],
            ['nombre' => 'Especias y Condimentos', 'descripcion' => 'Especias orgánicas y condimentos naturales'],
            ['nombre' => 'Bebidas Naturales', 'descripcion' => 'Jugos y bebidas 100% naturales'],
            ['nombre' => 'Snacks Saludables', 'descripcion' => 'Snacks orgánicos y nutritivos'],
            ['nombre' => 'Carnes y Aves', 'descripcion' => 'Carnes y aves de corral criadas naturalmente'],
            ['nombre' => 'Productos de Limpieza Ecológicos', 'descripcion' => 'Productos de limpieza biodegradables'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        $this->command->info('Categorías creadas exitosamente!');
    }
}