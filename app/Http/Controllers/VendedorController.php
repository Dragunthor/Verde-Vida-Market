<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\VendedorPerfil;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\VentaVendedor;
use Illuminate\Support\Facades\Storage;

class VendedorController extends Controller
{
    // Verificación interna para vendedor activo
    private function verificarVendedorActivo()
    {
        if (!auth()->user()->esVendedor()) {
            return redirect('/')->with('error', 'Acceso no autorizado. Debes ser vendedor.');
        }

        $perfilVendedor = auth()->user()->perfilVendedor;
        
        if (!$perfilVendedor || !$perfilVendedor->activo_vendedor) {
            return redirect('/vendedor/solicitud')->with('error', 'Tu cuenta de vendedor está pendiente de aprobación.');
        }

        return null; // Todo está bien
    }

    public function solicitud()
    {
        // Si ya es vendedor, redirigir al dashboard
        if (auth()->user()->esVendedor()) {
            $perfilVendedor = auth()->user()->perfilVendedor;
            if ($perfilVendedor && $perfilVendedor->activo_vendedor) {
                return redirect()->route('vendedor.dashboard');
            }
        }

        return view('vendedor.solicitud');
    }

    public function enviarSolicitud(Request $request)
    {
        // Validar que no sea ya vendedor
        if (auth()->user()->esVendedor()) {
            return redirect()->route('vendedor.dashboard')
                ->with('error', 'Ya eres vendedor en nuestra plataforma.');
        }

        $request->validate([
            'descripcion' => 'required|string|min:50|max:1000',
            'direccion' => 'required|string|max:500',
            'metodos_entrega' => 'required|in:recogida,delivery,ambos',
            'horario_atencion' => 'nullable|string|max:255',
            'terminos' => 'required|accepted'
        ]);

        // Crear perfil de vendedor (inactivo por defecto)
        VendedorPerfil::create([
            'usuario_id' => auth()->id(),
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'metodos_entrega' => $request->metodos_entrega,
            'horario_atencion' => $request->horario_atencion,
            'activo_vendedor' => false, // Requiere aprobación del admin
        ]);

        // Actualizar rol del usuario a vendedor (pero inactivo)
        auth()->user()->update(['rol' => 'vendedor']);

        return redirect()->route('vendedor.solicitud')
            ->with('success', '¡Solicitud enviada exitosamente! Nuestro equipo la revisará y te notificará por email una vez aprobada.');
    }

    public function dashboard()
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $usuario = auth()->user();
        
        // Usar VentaVendedor directamente para las estadísticas
        $ventasQuery = VentaVendedor::where('vendedor_id', $usuario->id);
        
        // Calcular pedidos pendientes (ventas con pedidos en estado pendiente)
        $pedidosPendientes = $ventasQuery->whereHas('pedido', function($query) {
            $query->where('estado', 'pendiente');
        })->count();

        $estadisticas = [
            'total_productos' => $usuario->productos()->count(),
            'productos_activos' => $usuario->productos()->where('activo', true)->count(),
            'productos_pendientes' => $usuario->productos()->where('aprobado', false)->count(),
            'total_ventas' => $ventasQuery->count(),
            'ventas_pendientes' => $ventasQuery->where('estado_pago', 'pendiente')->count(),
            'pedidos_pendientes' => $pedidosPendientes, // AGREGADO
        ];

        // Obtener pedidos recientes a través de VentaVendedor
        $pedidosRecientes = $ventasQuery
            ->with(['pedido.usuario', 'producto'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Obtener productos con stock bajo
        $stockBajo = $usuario->productos()
            ->where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->with('categoria')
            ->get();

        // Obtener productos pendientes de aprobación
        $productosPendientes = $usuario->productos()
            ->where('aprobado', false)
            ->with('categoria')
            ->get();

        return view('vendedor.dashboard', compact('estadisticas', 'pedidosRecientes', 'stockBajo', 'productosPendientes'));
    }

    public function productos()
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $productos = auth()->user()->productos()->with('categoria')->get();
        return view('vendedor.productos.index', compact('productos'));
    }

    public function crearProducto()
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $categorias = \App\Models\Categoria::all();
        return view('vendedor.productos.crear', compact('categorias'));
    }

    public function guardarProducto(Request $request)
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'required|integer|min:0',
            'unidad' => 'required|string',
            'origen' => 'required|string',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $productoData = $request->except('imagen');
        $productoData['vendedor_id'] = auth()->id();
        $productoData['aprobado'] = false; // Requiere aprobación del admin

        if ($request->hasFile('imagen')) {
            $productoData['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($productoData);

        return redirect('/vendedor/productos')->with('success', 'Producto creado. Esperando aprobación del administrador.');
    }

    public function editarProducto($id)
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $producto = auth()->user()->productos()->findOrFail($id);
        $categorias = \App\Models\Categoria::all();
        
        return view('vendedor.productos.editar', compact('producto', 'categorias'));
    }

    public function actualizarProducto(Request $request, $id)
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $producto = auth()->user()->productos()->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'required|integer|min:0',
            'unidad' => 'required|string',
            'origen' => 'required|string',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $productoData = $request->except('imagen');
        $productoData['aprobado'] = false; // Al editar, requiere nueva aprobación

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $productoData['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($productoData);

        return redirect('/vendedor/productos')->with('success', 'Producto actualizado. Esperando nueva aprobación del administrador.');
    }

    public function pedidos()
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        // Obtener pedidos a través de VentaVendedor con filtros
        $ventasQuery = VentaVendedor::where('vendedor_id', auth()->id())
            ->with(['pedido.usuario', 'producto']);

        // Filtrar por estado si se solicita
        if (request()->has('estado') && request('estado') != '') {
            $ventasQuery->whereHas('pedido', function($query) {
                $query->where('estado', request('estado'));
            });
        }

        $ventas = $ventasQuery->orderBy('created_at', 'desc')->get();

        return view('vendedor.pedidos.index', compact('ventas'));
    }
    public function ventas()
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        // Obtener ventas con filtros (TODAS para el listado)
        $ventasQuery = VentaVendedor::where('vendedor_id', auth()->id())
            ->with(['pedido.usuario', 'producto']);

        // Aplicar filtros
        if (request()->has('fecha_inicio') && request('fecha_inicio') != '') {
            $ventasQuery->whereDate('created_at', '>=', request('fecha_inicio'));
        }

        if (request()->has('fecha_fin') && request('fecha_fin') != '') {
            $ventasQuery->whereDate('created_at', '<=', request('fecha_fin'));
        }

        if (request()->has('estado_pago') && request('estado_pago') != '') {
            $ventasQuery->where('estado_pago', request('estado_pago'));
        }

        if (request()->has('producto') && request('producto') != '') {
            $ventasQuery->where('producto_id', request('producto'));
        }

        $ventas = $ventasQuery->orderBy('created_at', 'desc')->get();

        // Obtener solo ventas PAGADAS para estadísticas de ingresos
        $ventasPagadas = $ventas->where('estado_pago', 'pagado');

        // Estadísticas generales - SOLO VENTAS PAGADAS para ingresos
        $estadisticasVentas = [
            'total_ventas' => $ventas->count(), // Todas las ventas para conteo
            'ventas_pagadas' => $ventasPagadas->count(),
            'ingresos_totales' => $ventasPagadas->sum('total_vendedor'), // Solo pagadas
            'comisiones_totales' => $ventasPagadas->sum(function($venta) { // Solo pagadas
                return ($venta->precio_venta * $venta->cantidad * $venta->comision_porcentaje) / 100;
            })
        ];

        // Ventas agrupadas por producto - TODAS para análisis
        $ventasPorProducto = $ventas->groupBy('producto_id')->map(function($ventasProducto) {
            $producto = $ventasProducto->first()->producto;
            $ventasPagadasProducto = $ventasProducto->where('estado_pago', 'pagado');
            
            return (object)[
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'unidad' => $producto->unidad,
                'total_ventas' => $ventasProducto->count(), // Todas
                'ventas_pagadas' => $ventasPagadasProducto->count(), // Solo pagadas
                'cantidad_total' => $ventasProducto->sum('cantidad'), // Todas
                'total_ingresos' => $ventasPagadasProducto->sum('total_vendedor') // Solo pagadas
            ];
        })->values();

        // Ventas agrupadas por mes - TODAS para análisis
        $ventasPorMes = $ventas->groupBy(function($venta) {
            return $venta->created_at->format('Y-m');
        })->map(function($ventasMes, $key) {
            $ventasPagadasMes = $ventasMes->where('estado_pago', 'pagado');
            
            return (object)[
                'mes' => \Carbon\Carbon::parse($key)->format('m'),
                'anio' => \Carbon\Carbon::parse($key)->format('Y'),
                'total_ventas' => $ventasMes->count(), // Todas
                'ventas_pagadas' => $ventasPagadasMes->count(), // Solo pagadas
                'total_ingresos' => $ventasPagadasMes->sum('total_vendedor') // Solo pagadas
            ];
        })->values()->sortByDesc(function($item) {
            return $item->anio . $item->mes;
        });

        // Lista de productos vendidos para el filtro
        $productosVendidos = auth()->user()->productos()
            ->whereHas('ventasVendedor')
            ->get();

        return view('vendedor.ventas.index', compact(
            'ventas', 
            'estadisticasVentas', 
            'ventasPorProducto', 
            'ventasPorMes',
            'productosVendidos'
        ));
    }
}