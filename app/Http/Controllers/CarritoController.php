<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session('carrito', []);
        $total = 0;
        
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return view('carrito.index', compact('carrito', 'total'));
    }

    public function agregar(Request $request)
    {
        $productoId = $request->input('producto_id');
        $cantidad = $request->input('cantidad', 1);

        // Datos de ejemplo del producto
        $producto = [
            'id' => $productoId,
            'nombre' => 'Producto ' . $productoId,
            'precio' => 10.00 * $productoId,
            'imagen' => 'placeholder.jpg',
            'unidad' => 'Kg',
            'stock' => 50
        ];

        $carrito = session('carrito', []);
        
        // Verificar si el producto ya está en el carrito
        $encontrado = false;
        foreach ($carrito as &$item) {
            if ($item['id'] == $productoId) {
                $item['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            $carrito[] = [
                'id' => $productoId,
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'imagen' => $producto['imagen'],
                'unidad' => $producto['unidad'],
                'cantidad' => $cantidad,
                'stock' => $producto['stock']
            ];
        }

        session(['carrito' => $carrito]);

        return redirect()->back()->with('success', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request)
    {
        $cantidades = $request->input('cantidades', []);
        $carrito = session('carrito', []);

        foreach ($cantidades as $id => $cantidad) {
            foreach ($carrito as &$item) {
                if ($item['id'] == $id) {
                    if ($cantidad <= 0) {
                        // Eliminar item
                        $carrito = array_filter($carrito, function($item) use ($id) {
                            return $item['id'] != $id;
                        });
                    } else {
                        $item['cantidad'] = $cantidad;
                    }
                    break;
                }
            }
        }

        session(['carrito' => array_values($carrito)]);

        return redirect()->back()->with('success', 'Carrito actualizado correctamente.');
    }

    public function eliminar($id)
    {
        $carrito = session('carrito', []);
        $carrito = array_filter($carrito, function($item) use ($id) {
            return $item['id'] != $id;
        });

        session(['carrito' => array_values($carrito)]);

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }
}