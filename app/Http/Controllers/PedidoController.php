<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // Eliminar el constructor con middleware - lo manejaremos en las rutas
    
    public function checkout()
    {
        // Verificar autenticación manualmente
        if (!session('usuario')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para realizar un pedido.');
        }

        $carrito = session('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        $usuario = session('usuario');

        return view('pedidos.checkout', compact('carrito', 'total', 'usuario'));
    }

    public function procesarPedido(Request $request)
    {
        // Verificar autenticación manualmente
        if (!session('usuario')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para realizar un pedido.');
        }

        $carrito = session('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        // Simular creación de pedido
        $pedidoId = rand(1000, 9999);
        
        // Limpiar carrito
        session(['carrito' => []]);

        return redirect()->route('pedidos.confirmacion', $pedidoId);
    }

    public function confirmacion($id)
    {
        // Verificar autenticación manualmente
        if (!session('usuario')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver esta página.');
        }

        // Datos de ejemplo del pedido
        $pedido = [
            'id' => $id,
            'total' => 45.50,
            'estado' => 'pendiente',
            'metodo_pago' => 'efectivo',
            'fecha_pedido' => now()->format('d/m/Y H:i'),
            'fecha_entrega' => now()->addDay()->format('d/m/Y H:i'),
            'notas' => 'Entregar en la puerta principal',
            'usuario_nombre' => 'Usuario Demo',
            'email' => 'demo@example.com',
            'telefono' => '987654321',
            'direccion' => 'Av. Principal 123, Lima, Perú'
        ];

        $detalles = [
            [
                'producto_nombre' => 'Manzanas Orgánicas',
                'cantidad' => 2,
                'precio' => 8.50,
                'unidad' => 'Kg'
            ],
            [
                'producto_nombre' => 'Plátanos Ecológicos',
                'cantidad' => 1,
                'precio' => 6.80,
                'unidad' => 'Kg'
            ]
        ];

        return view('pedidos.confirmacion', compact('pedido', 'detalles'));
    }

    public function historial()
    {
        // Verificar autenticación manualmente
        if (!session('usuario')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tu historial.');
        }

        $pedidos = [
            [
                'id' => 1001,
                'total' => 45.50,
                'estado' => 'entregado',
                'fecha_pedido' => '15/10/2023 14:30',
                'metodo_pago' => 'efectivo'
            ],
            [
                'id' => 1002,
                'total' => 32.80,
                'estado' => 'preparando',
                'fecha_pedido' => '16/10/2023 10:15',
                'metodo_pago' => 'transferencia'
            ]
        ];

        return view('pedidos.historial', compact('pedidos'));
    }

    public function show($id)
    {
        // Verificar autenticación manualmente
        if (!session('usuario')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver esta página.');
        }

        $pedido = [
            'id' => $id,
            'total' => 45.50,
            'estado' => 'preparando',
            'metodo_pago' => 'efectivo',
            'fecha_pedido' => '16/10/2023 10:15',
            'fecha_entrega' => '17/10/2023 14:00',
            'notas' => 'Entregar en la puerta principal',
            'usuario_nombre' => 'Usuario Demo',
            'email' => 'demo@example.com',
            'telefono' => '987654321',
            'direccion' => 'Av. Principal 123, Lima, Perú'
        ];

        $detalles = [
            [
                'producto_nombre' => 'Manzanas Orgánicas',
                'cantidad' => 2,
                'precio' => 8.50,
                'unidad' => 'Kg',
                'imagen' => 'placeholder.jpg'
            ],
            [
                'producto_nombre' => 'Plátanos Ecológicos',
                'cantidad' => 1,
                'precio' => 6.80,
                'unidad' => 'Kg',
                'imagen' => 'placeholder.jpg'
            ]
        ];

        return view('pedidos.show', compact('pedido', 'detalles'));
    }
}