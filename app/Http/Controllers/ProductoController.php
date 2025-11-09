<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        // Datos de ejemplo para la vista
        $productosDestacados = [
            [
                'id' => 1,
                'nombre' => 'Manzanas Orgánicas',
                'descripcion' => 'Manzanas frescas cultivadas localmente sin pesticidas',
                'precio' => 8.50,
                'imagen' => 'placeholder.jpg',
                'unidad' => 'Kg'
            ],
            [
                'id' => 2,
                'nombre' => 'Plátanos Ecológicos',
                'descripcion' => 'Plátanos de cultivo ecológico y sostenible',
                'precio' => 6.80,
                'imagen' => 'placeholder.jpg',
                'unidad' => 'Kg'
            ],
            [
                'id' => 3,
                'nombre' => 'Lechuga Romana',
                'descripcion' => 'Lechuga romana fresca de cultivo orgánico',
                'precio' => 4.20,
                'imagen' => 'placeholder.jpg',
                'unidad' => 'Unidad'
            ]
        ];

        $categorias = [
            ['id' => 1, 'nombre' => 'Frutas Orgánicas'],
            ['id' => 2, 'nombre' => 'Verduras Ecológicas'],
            ['id' => 3, 'nombre' => 'Lácteos Naturales']
        ];

        return view('productos.index', compact('productosDestacados', 'categorias'));
    }

    public function catalog(Request $request)
    {
        $categoriaId = $request->get('categoria');
        $busqueda = $request->get('busqueda');

        // Datos de ejemplo
        $productos = [
            [
                'id' => 1,
                'nombre' => 'Manzanas Orgánicas',
                'descripcion' => 'Manzanas frescas cultivadas localmente sin pesticidas',
                'precio' => 8.50,
                'imagen' => 'placeholder.jpg',
                'unidad' => 'Kg',
                'stock' => 50,
                'origen' => 'Local',
                'categoria_nombre' => 'Frutas Orgánicas'
            ],
            [
                'id' => 2,
                'nombre' => 'Plátanos Ecológicos',
                'descripcion' => 'Plátanos de cultivo ecológico y sostenible',
                'precio' => 6.80,
                'imagen' => 'placeholder.jpg',
                'unidad' => 'Kg',
                'stock' => 30,
                'origen' => 'Local',
                'categoria_nombre' => 'Frutas Orgánicas'
            ],
            [
                'id' => 3,
                'nombre' => 'Lechuga Romana',
                'descripcion' => 'Lechuga romana fresca de cultivo orgánico',
                'precio' => 4.20,
                'imagen' => 'placeholder.jpg',
                'unidad' => 'Unidad',
                'stock' => 25,
                'origen' => 'Local',
                'categoria_nombre' => 'Verduras Ecológicas'
            ]
        ];

        $categorias = [
            ['id' => 1, 'nombre' => 'Frutas Orgánicas'],
            ['id' => 2, 'nombre' => 'Verduras Ecológicas'],
            ['id' => 3, 'nombre' => 'Lácteos Naturales']
        ];

        return view('productos.catalog', compact('productos', 'categorias', 'categoriaId', 'busqueda'));
    }

    public function show($id)
    {
        // Datos de ejemplo
        $producto = [
            'id' => $id,
            'nombre' => 'Manzanas Orgánicas',
            'descripcion' => "Manzanas frescas cultivadas localmente sin pesticidas. Nuestras manzanas son cosechadas en el momento óptimo de maduración para garantizar el mejor sabor y textura.\n\nBeneficios:\n• Rico en fibra y vitaminas\n• Cultivo sostenible\n• Sin químicos ni conservantes",
            'precio' => 8.50,
            'imagen' => 'placeholder.jpg',
            'unidad' => 'Kg',
            'stock' => 50,
            'origen' => 'Local',
            'categoria_id' => 1,
            'categoria_nombre' => 'Frutas Orgánicas'
        ];

        $relacionados = [
            [
                'id' => 2,
                'nombre' => 'Plátanos Ecológicos',
                'descripcion' => 'Plátanos de cultivo ecológico y sostenible',
                'precio' => 6.80,
                'imagen' => 'placeholder.jpg',
                'unidad' => 'Kg'
            ],
            [
                'id' => 4,
                'nombre' => 'Tomates Cherry',
                'descripcion' => 'Tomates cherry cultivados naturalmente',
                'precio' => 7.90,
                'imagen' => 'placeholder.jpg',
                'unidad' => 'Kg'
            ]
        ];

        return view('productos.show', compact('producto', 'relacionados'));
    }
}