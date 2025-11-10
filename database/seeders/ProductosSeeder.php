<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Usuario;

class ProductosSeeder extends Seeder
{
    public function run()
    {
        // Obtener categorías
        $frutas = Categoria::where('nombre', 'Frutas Orgánicas')->first();
        $lacteos = Categoria::where('nombre', 'Lácteos Naturales')->first();
        $verduras = Categoria::where('nombre', 'Verduras Ecológicas')->first();

        // Obtener el admin como vendedor
        $adminVendedor = Usuario::where('email', 'admin@verdevida.com')->first();

        $productos = [
            // Frutas Orgánicas
            [
                'categoria_id' => $frutas->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Manzanas Fuji Orgánicas',
                'descripcion' => 'Manzanas Fuji cultivadas de forma orgánica, crujientes y dulces. Perfectas para snacks saludables.',
                'precio' => 3.50,
                'imagen' => 'productos/manzanas-fuji.jpg',
                'stock' => 50,
                'unidad' => 'kg',
                'origen' => 'Huerto La Montaña',
                'activo' => true,
                'aprobado' => true,
            ],
            [
                'categoria_id' => $frutas->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Plátanos Maduros Orgánicos',
                'descripcion' => 'Plátanos madurados naturalmente, dulces y cremosos. Ideales para batidos y postres.',
                'precio' => 2.80,
                'imagen' => 'productos/platanos-maduros.jpg',
                'stock' => 30,
                'unidad' => 'kg',
                'origen' => 'Finca El Platanar',
                'activo' => true,
                'aprobado' => true,
            ],
            [
                'categoria_id' => $frutas->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Fresas Frescas Orgánicas',
                'descripcion' => 'Fresas rojas y jugosas, cultivadas sin pesticidas. Deliciosas en ensaladas y desayunos.',
                'precio' => 4.20,
                'imagen' => 'productos/fresas-frescas.jpg',
                'stock' => 25,
                'unidad' => 'kg',
                'origen' => 'Granja Las Fresas',
                'activo' => true,
                'aprobado' => true,
            ],

            // Lácteos Naturales
            [
                'categoria_id' => $lacteos->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Queso Fresco Campesino',
                'descripcion' => 'Queso fresco elaborado artesanalmente con leche de vacas criadas en libertad.',
                'precio' => 8.75,
                'imagen' => 'productos/queso-fresco.jpg',
                'stock' => 15,
                'unidad' => 'pieza (500g)',
                'origen' => 'Quesería La Vaquita',
                'activo' => true,
                'aprobado' => true,
            ],
            [
                'categoria_id' => $lacteos->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Yogurt Natural Probiotico',
                'descripcion' => 'Yogurt natural con cultivos probióticos vivos, sin conservantes ni azúcares añadidos.',
                'precio' => 5.30,
                'imagen' => 'productos/yogurt-natural.jpg',
                'stock' => 20,
                'unidad' => 'litro',
                'origen' => 'Lácteos Saludables',
                'activo' => true,
                'aprobado' => true,
            ],
            [
                'categoria_id' => $lacteos->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Leche Entera Orgánica',
                'descripcion' => 'Leche 100% orgánica de vacas alimentadas con pasto, rica en nutrientes y sabor auténtico.',
                'precio' => 3.90,
                'imagen' => 'productos/leche-entera.jpg',
                'stock' => 35,
                'unidad' => 'litro',
                'origen' => 'Granja La Esperanza',
                'activo' => true,
                'aprobado' => true,
            ],

            // Verduras Ecológicas
            [
                'categoria_id' => $verduras->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Tomates Cherry Orgánicos',
                'descripcion' => 'Tomates cherry dulces y jugosos, perfectos para ensaladas y guarniciones.',
                'precio' => 4.50,
                'imagen' => 'productos/tomates-cherry.jpg',
                'stock' => 40,
                'unidad' => 'kg',
                'origen' => 'Huerto Familiar',
                'activo' => true,
                'aprobado' => true,
            ],
            [
                'categoria_id' => $verduras->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Zanahorias Frescas Orgánicas',
                'descripcion' => 'Zanahorias crujientes y dulces, ricas en betacaroteno y vitaminas.',
                'precio' => 2.95,
                'imagen' => 'productos/zanahorias-frescas.jpg',
                'stock' => 60,
                'unidad' => 'kg',
                'origen' => 'Campo Verde',
                'activo' => true,
                'aprobado' => true,
            ],
            [
                'categoria_id' => $verduras->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Lechuga Romana Ecológica',
                'descripcion' => 'Lechuga romana fresca y crujiente, ideal para ensaladas y sandwiches saludables.',
                'precio' => 2.25,
                'imagen' => 'productos/lechuga-romana.jpg',
                'stock' => 28,
                'unidad' => 'pieza',
                'origen' => 'Invernadero Natural',
                'activo' => true,
                'aprobado' => true,
            ],
            [
                'categoria_id' => $verduras->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Espinacas Frescas Orgánicas',
                'descripcion' => 'Espinacas tiernas y nutritivas, perfectas para cremas, salteados y ensaladas.',
                'precio' => 3.15,
                'imagen' => 'productos/espinacas-frescas.jpg',
                'stock' => 22,
                'unidad' => 'kg',
                'origen' => 'Huerto Las Hojas Verdes',
                'activo' => true,
                'aprobado' => true,
            ],
            [
                'categoria_id' => $verduras->id,
                'vendedor_id' => $adminVendedor->id,
                'nombre' => 'Brócoli Orgánico Fresco',
                'descripcion' => 'Brócoli fresco y compacto, rico en antioxidantes y vitaminas esenciales.',
                'precio' => 4.80,
                'imagen' => 'productos/brocoli-organico.jpg',
                'stock' => 18,
                'unidad' => 'kg',
                'origen' => 'Cultivos Sostenibles',
                'activo' => true,
                'aprobado' => true,
            ]
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }

        $this->command->info('Productos creados exitosamente!');
        $this->command->info('Total: ' . count($productos) . ' productos en 3 categorías');
    }
}