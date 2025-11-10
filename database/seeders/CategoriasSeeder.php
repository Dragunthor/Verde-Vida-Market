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
            [
                'nombre' => 'Frutas Orgánicas',
                'descripcion' => 'Frutas frescas cultivadas sin pesticidas ni químicos, 100% naturales y llenas de sabor.',
                'imagen' => 'categorias/frutas-organicas.jpg'
            ],
            [
                'nombre' => 'Lácteos Naturales',
                'descripcion' => 'Productos lácteos de animales criados en libertad, sin hormonas ni antibióticos.',
                'imagen' => 'categorias/lacteos-naturales.jpg'
            ],
            [
                'nombre' => 'Verduras Ecológicas',
                'descripcion' => 'Verduras cultivadas de manera sostenible, ricas en nutrientes y sabor auténtico.',
                'imagen' => 'categorias/verduras-ecologicas.jpg'
            ],
            [
                'nombre' => 'Granos y Cereales',
                'descripcion' => 'Granos integrales y cereales orgánicos, base de una alimentación saludable.',
                'imagen' => 'categorias/granos-cereales.jpg'
            ],
            [
                'nombre' => 'Hierbas y Especias',
                'descripcion' => 'Hierbas aromáticas y especias cultivadas naturalmente para realzar tus comidas.',
                'imagen' => 'categorias/hierbas-especias.jpg'
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        $this->command->info('Categorías creadas exitosamente!');
    }
}