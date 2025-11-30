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
        $categorias = Categoria::all();
        $vendedores = Usuario::where('rol', 'vendedor')->orWhere('rol', 'admin')->get();

        $productos = [
            // Frutas Frescas
            [
                'nombre' => 'Plátanos Orgánicos',
                'descripcion' => 'Plátanos maduros cultivados de forma orgánica, perfectos para batidos o comer frescos.',
                'precio' => 3.50,
                'stock' => 50,
                'unidad' => 'kg',
                'origen' => 'Chincha, Ica',
                'imagen' => 'platano.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Frutas Frescas')->first()->id,
            ],
            [
                'nombre' => 'Manzanas Royal Gala',
                'descripcion' => 'Manzanas crujientes y dulces, ideales para snacks saludables.',
                'precio' => 4.20,
                'stock' => 35,
                'unidad' => 'kg',
                'origen' => 'Huancayo, Junín',
                'imagen' => 'manzana.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Frutas Frescas')->first()->id,
            ],
            [
                'nombre' => 'Naranjas Valencia',
                'descripcion' => 'Naranjas jugosas y dulces, ricas en vitamina C.',
                'precio' => 2.80,
                'stock' => 60,
                'unidad' => 'kg',
                'origen' => 'Satipo, Junín',
                'imagen' => 'naranja.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Frutas Frescas')->first()->id,
            ],
            [
                'nombre' => 'Aguacates Hass',
                'descripcion' => 'Aguacates cremosos perfectos para guacamole o tostadas.',
                'precio' => 8.50,
                'stock' => 25,
                'unidad' => 'kg',
                'origen' => 'Moyobamba, San Martín',
                'imagen' => 'aguacate.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Frutas Frescas')->first()->id,
            ],
            [
                'nombre' => 'Fresas Orgánicas',
                'descripcion' => 'Fresas dulces y aromáticas, cultivadas sin pesticidas.',
                'precio' => 12.00,
                'stock' => 15,
                'unidad' => 'kg',
                'origen' => 'Huaura, Lima',
                'imagen' => 'fresa.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Frutas Frescas')->first()->id,
            ],

            // Verduras Orgánicas
            [
                'nombre' => 'Tomates Cherry Orgánicos',
                'descripcion' => 'Tomates cherry dulces y jugosos, perfectos para ensaladas.',
                'precio' => 6.80,
                'stock' => 30,
                'unidad' => 'kg',
                'origen' => 'Cañete, Lima',
                'imagen' => 'tomate.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Verduras Orgánicas')->first()->id,
            ],
            [
                'nombre' => 'Zanahorias Baby',
                'descripcion' => 'Zanahorias tiernas y dulces, ricas en betacaroteno.',
                'precio' => 3.20,
                'stock' => 40,
                'unidad' => 'kg',
                'origen' => 'Huancayo, Junín',
                'imagen' => 'zanahoria.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Verduras Orgánicas')->first()->id,
            ],
            [
                'nombre' => 'Espinacas Frescas',
                'descripcion' => 'Espinacas tiernas y nutritivas, ideales para batidos y ensaladas.',
                'precio' => 4.50,
                'stock' => 20,
                'unidad' => 'atado',
                'origen' => 'Chancay, Lima',
                'imagen' => 'espinaca.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Verduras Orgánicas')->first()->id,
            ],
            [
                'nombre' => 'Brócoli Orgánico',
                'descripcion' => 'Brócoli fresco y crujiente, rico en antioxidantes.',
                'precio' => 5.80,
                'stock' => 25,
                'unidad' => 'kg',
                'origen' => 'Huaura, Lima',
                'imagen' => 'brocoli.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Verduras Orgánicas')->first()->id,
            ],
            [
                'nombre' => 'Pimientos Tricolor',
                'descripcion' => 'Mezcla de pimientos rojos, amarillos y verdes.',
                'precio' => 7.50,
                'stock' => 18,
                'unidad' => 'kg',
                'origen' => 'Ica, Ica',
                'imagen' => 'pimiento.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Verduras Orgánicas')->first()->id,
            ],

            // Lácteos y Huevos
            [
                'nombre' => 'Huevos Camperos',
                'descripcion' => 'Huevos de gallinas criadas en libertad, yemas color naranja intenso.',
                'precio' => 12.50,
                'stock' => 48,
                'unidad' => 'docena',
                'origen' => 'Huacho, Lima',
                'imagen' => 'huevos.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Lácteos y Huevos')->first()->id,
            ],
            [
                'nombre' => 'Queso Fresco Andino',
                'descripcion' => 'Queso fresco artesanal de leche entera.',
                'precio' => 18.00,
                'stock' => 12,
                'unidad' => 'kg',
                'origen' => 'Cajamarca, Cajamarca',
                'imagen' => 'queso.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Lácteos y Huevos')->first()->id,
            ],
            [
                'nombre' => 'Yogurt Natural Griego',
                'descripcion' => 'Yogurt griego cremoso sin azúcar añadido.',
                'precio' => 8.50,
                'stock' => 24,
                'unidad' => 'unidad',
                'origen' => 'Arequipa, Arequipa',
                'imagen' => 'yogurt.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Lácteos y Huevos')->first()->id,
            ],

            // Panadería y Granos
            [
                'nombre' => 'Pan Integral Artesanal',
                'descripcion' => 'Pan integral hecho con harina de trigo orgánico.',
                'precio' => 6.50,
                'stock' => 15,
                'unidad' => 'unidad',
                'origen' => 'Lima, Lima',
                'imagen' => 'pan.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Panadería y Granos')->first()->id,
            ],
            [
                'nombre' => 'Quinua Orgánica',
                'descripcion' => 'Quinua blanca orgánica de los Andes peruanos.',
                'precio' => 15.00,
                'stock' => 30,
                'unidad' => 'kg',
                'origen' => 'Puno, Puno',
                'imagen' => 'quinua.png',
                'activo' => true,
                'aprobado' => true,
                'categoria_id' => $categorias->where('nombre', 'Panadería y Granos')->first()->id,
            ],

            // Productos pendientes de aprobación (para probar el admin)
            [
                'nombre' => 'Miel de Abeja Pura',
                'descripcion' => 'Miel 100% pura de abejas silvestres.',
                'precio' => 25.00,
                'stock' => 10,
                'unidad' => 'L',
                'origen' => 'Oxapampa, Pasco',
                'imagen' => 'miel.png',
                'activo' => true,
                'aprobado' => false,
                'categoria_id' => $categorias->where('nombre', 'Bebidas Naturales')->first()->id,
            ],
            [
                'nombre' => 'Aceite de Oliva Extra Virgen',
                'descripcion' => 'Aceite de oliva de primera prensada en frío.',
                'precio' => 35.00,
                'stock' => 8,
                'unidad' => 'L',
                'origen' => 'Ica, Ica',
                'imagen' => 'aceite.png',
                'activo' => true,
                'aprobado' => false,
                'categoria_id' => $categorias->where('nombre', 'Especias y Condimentos')->first()->id,
            ],
        ];

        foreach ($productos as $producto) {
            // Asignar vendedor aleatorio
            $producto['vendedor_id'] = $vendedores->random()->id;
            Producto::create($producto);
        }

        $this->command->info('Productos creados exitosamente!');
        $this->command->info('Total: ' . count($productos) . ' productos');
        $this->command->info('Productos pendientes: ' . collect($productos)->where('aprobado', false)->count());
    }
}