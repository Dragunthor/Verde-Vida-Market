<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Eliminar el constructor con middleware - lo manejaremos manualmente
    
    public function dashboard()
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $stats = [
            'total_pedidos' => 150,
            'pedidos_pendientes' => 12,
            'total_clientes' => 89,
            'total_productos' => 45,
            'ventas_totales' => 12540.75
        ];

        $pedidosRecientes = [
            [
                'id' => 1001,
                'cliente_nombre' => 'Juan Pérez',
                'total' => 45.50,
                'estado' => 'pendiente',
                'fecha_pedido' => '16/10/2023 14:30'
            ],
            [
                'id' => 1002,
                'cliente_nombre' => 'María García',
                'total' => 32.80,
                'estado' => 'confirmado',
                'fecha_pedido' => '16/10/2023 10:15'
            ]
        ];

        $stockBajo = [
            [
                'id' => 5,
                'nombre' => 'Queso Fresco Artesanal',
                'stock' => 3
            ],
            [
                'id' => 8,
                'nombre' => 'Miel Orgánica',
                'stock' => 5
            ]
        ];

        return view('admin.dashboard', compact('stats', 'pedidosRecientes', 'stockBajo'));
    }

    public function productos()
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $productos = [
            [
                'id' => 1,
                'nombre' => 'Manzanas Orgánicas',
                'categoria_nombre' => 'Frutas Orgánicas',
                'precio' => 8.50,
                'stock' => 50,
                'unidad' => 'Kg',
                'activo' => true,
                'imagen' => 'placeholder.jpg'
            ],
            [
                'id' => 2,
                'nombre' => 'Plátanos Ecológicos',
                'categoria_nombre' => 'Frutas Orgánicas',
                'precio' => 6.80,
                'stock' => 30,
                'unidad' => 'Kg',
                'activo' => true,
                'imagen' => 'placeholder.jpg'
            ]
        ];

        return view('admin.productos.index', compact('productos'));
    }

    public function crearProducto()
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $categorias = [
            ['id' => 1, 'nombre' => 'Frutas Orgánicas'],
            ['id' => 2, 'nombre' => 'Verduras Ecológicas'],
            ['id' => 3, 'nombre' => 'Lácteos Naturales']
        ];

        return view('admin.productos.create', compact('categorias'));
    }

    public function editarProducto($id)
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $producto = [
            'id' => $id,
            'nombre' => 'Manzanas Orgánicas',
            'descripcion' => 'Manzanas frescas cultivadas localmente sin pesticidas',
            'precio' => 8.50,
            'stock' => 50,
            'unidad' => 'Kg',
            'origen' => 'Local',
            'categoria_id' => 1,
            'activo' => true,
            'imagen' => 'placeholder.jpg'
        ];

        $categorias = [
            ['id' => 1, 'nombre' => 'Frutas Orgánicas'],
            ['id' => 2, 'nombre' => 'Verduras Ecológicas'],
            ['id' => 3, 'nombre' => 'Lácteos Naturales']
        ];

        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    public function categorias()
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $categorias = [
            [
                'id' => 1,
                'nombre' => 'Frutas Orgánicas',
                'descripcion' => 'Frutas frescas de cultivo local',
                'imagen' => 'placeholder.jpg',
                'created_at' => '2023-01-15'
            ],
            [
                'id' => 2,
                'nombre' => 'Verduras Ecológicas',
                'descripcion' => 'Verduras de temporada sin pesticidas',
                'imagen' => 'placeholder.jpg',
                'created_at' => '2023-01-15'
            ]
        ];

        return view('admin.categorias.index', compact('categorias'));
    }

    public function crearCategoria()
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return view('admin.categorias.create');
    }

    public function editarCategoria($id)
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $categoria = [
            'id' => $id,
            'nombre' => 'Frutas Orgánicas',
            'descripcion' => 'Frutas frescas de cultivo local',
            'imagen' => 'placeholder.jpg'
        ];

        return view('admin.categorias.edit', compact('categoria'));
    }

    public function pedidos()
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $pedidos = [
            [
                'id' => 1001,
                'cliente_nombre' => 'Juan Pérez',
                'total' => 45.50,
                'estado' => 'pendiente',
                'fecha_pedido' => '16/10/2023 14:30',
                'metodo_pago' => 'efectivo'
            ],
            [
                'id' => 1002,
                'cliente_nombre' => 'María García',
                'total' => 32.80,
                'estado' => 'confirmado',
                'fecha_pedido' => '16/10/2023 10:15',
                'metodo_pago' => 'transferencia'
            ]
        ];

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function gestionarPedido($id)
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $pedido = [
            'id' => $id,
            'cliente_nombre' => 'Juan Pérez',
            'total' => 45.50,
            'estado' => 'pendiente',
            'metodo_pago' => 'efectivo',
            'fecha_pedido' => '16/10/2023 14:30',
            'fecha_entrega' => '17/10/2023 14:00',
            'notas' => 'Entregar en la puerta principal',
            'usuario_nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
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

        return view('admin.pedidos.gestionar', compact('pedido', 'detalles'));
    }

    public function clientes()
    {
        // Verificar manualmente si es admin
        $usuario = session('usuario');
        if (!$usuario || $usuario['rol'] !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $clientes = [
            [
                'nombre' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'telefono' => '987654321',
                'fecha_registro' => '15/01/2023',
                'total_pedidos' => 5,
                'total_compras' => 245.80,
                'activo' => true
            ],
            [
                'nombre' => 'María García',
                'email' => 'maria@example.com',
                'telefono' => '987654322',
                'fecha_registro' => '20/02/2023',
                'total_pedidos' => 3,
                'total_compras' => 132.50,
                'activo' => true
            ]
        ];

        return view('admin.clientes.index', compact('clientes'));
    }
}