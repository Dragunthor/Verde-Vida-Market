<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\VendedorPerfil;
use App\Models\Pedido;
use App\Models\Reporte;
use App\Models\VentaVendedor;
use App\Models\ResenaProducto;
use App\Models\ResenaVendedor;

class AdminController extends Controller
{
    // Verificación interna para admin
    private function verificarAdmin()
    {
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado. Debes ser administrador.');
        }
        return null; // Todo está bien
    }

    public function __construct()
    {
        // Compartir datos con todas las vistas del admin
        view()->composer('layouts.admin', function ($view) {
            $vendedoresPendientesCount = VendedorPerfil::where('activo_vendedor', false)->count();
            $productosPendientesCount = Producto::where('aprobado', false)->count();
            $reportesPendientesCount = Reporte::where('estado', 'pendiente')->count();
            $resenasPendientesCount = ResenaProducto::where('aprobado', false)->count() + 
                                     ResenaVendedor::where('aprobado', false)->count();

            $view->with([
                'vendedoresPendientesCount' => $vendedoresPendientesCount,
                'productosPendientesCount' => $productosPendientesCount,
                'reportesPendientesCount' => $reportesPendientesCount,
                'resenasPendientesCount' => $resenasPendientesCount,
            ]);
        });
    }

    public function dashboard()
    {
        // Verificar que el usuario es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

        // Estadísticas principales
        $estadisticas = [
            'ingresos_totales' => Pedido::where('estado', 'entregado')->sum('total'),
            'total_pedidos' => Pedido::count(),
            'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'total_usuarios' => Usuario::count(),
            'vendedores_pendientes' => VendedorPerfil::where('activo_vendedor', false)->count(),
            'productos_pendientes' => Producto::where('aprobado', false)->count(),
            'reportes_pendientes' => Reporte::where('estado', 'pendiente')->count(),
            'resenas_pendientes' => ResenaProducto::where('aprobado', false)->count() + 
                                   ResenaVendedor::where('aprobado', false)->count(),
        ];

        // Datos para las secciones
        $pedidosRecientes = Pedido::with('usuario')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $vendedoresPendientes = VendedorPerfil::with('usuario')
            ->where('activo_vendedor', false)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $stockBajo = Producto::with('vendedor')
            ->where('stock', '<', 10)
            ->where('stock', '>', 0)
            ->where('aprobado', true)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        $reportesRecientes = Reporte::with('usuario')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'estadisticas',
            'pedidosRecientes',
            'vendedoresPendientes',
            'stockBajo',
            'reportesRecientes'
        ));
    }

    public function vendedores()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $vendedoresPendientes = VendedorPerfil::with('usuario')
            ->where('activo_vendedor', false)
            ->get();

        $vendedoresActivos = VendedorPerfil::with('usuario')
            ->where('activo_vendedor', true)
            ->get();

        return view('admin.vendedores.index', compact('vendedoresPendientes', 'vendedoresActivos'));
    }

    public function aprobarVendedor($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $vendedorPerfil = VendedorPerfil::findOrFail($id);
        $vendedorPerfil->update(['activo_vendedor' => true]);

        return redirect('/admin/vendedores')->with('success', 'Vendedor aprobado exitosamente.');
    }

    public function desactivarVendedor($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $vendedorPerfil = VendedorPerfil::findOrFail($id);
        $vendedorPerfil->update(['activo_vendedor' => false]);

        return redirect('/admin/vendedores')->with('success', 'Vendedor desactivado exitosamente.');
    }

    public function productos()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $productosPendientes = Producto::with('vendedor', 'categoria')
            ->where('aprobado', false)
            ->get();

        $productosActivos = Producto::with('vendedor', 'categoria')
            ->where('aprobado', true)
            ->get();

        return view('admin.productos.index', compact('productosPendientes', 'productosActivos'));
    }

    public function aprobarProducto($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $producto = Producto::findOrFail($id);
        $producto->update(['aprobado' => true]);

        return redirect('/admin/productos')->with('success', 'Producto aprobado exitosamente.');
    }

    // Métodos para Reportes
    public function reportes()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $reportesPendientes = Reporte::with('usuario')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();

        $reportesEnRevision = Reporte::with('usuario')
            ->where('estado', 'en_revision')
            ->orderBy('created_at', 'desc')
            ->get();

        $reportesResueltos = Reporte::with('usuario')
            ->where('estado', 'resuelto')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reportes.index', compact('reportesPendientes', 'reportesEnRevision', 'reportesResueltos'));
    }

    public function mostrarReporte($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $reporte = Reporte::with(['usuario', 'mensajes'])->findOrFail($id);
        
        // Obtener el objeto reportado según el tipo
        $objetoReportado = null;
        switch ($reporte->tipo) {
            case 'producto':
                $objetoReportado = \App\Models\Producto::find($reporte->objeto_id);
                break;
            case 'vendedor':
                $objetoReportado = \App\Models\Usuario::find($reporte->objeto_id);
                break;
            case 'pedido':
                $objetoReportado = \App\Models\Pedido::find($reporte->objeto_id);
                break;
        }

        return view('admin.reportes.show', compact('reporte', 'objetoReportado'));
    }

    public function actualizarEstadoReporte(Request $request, $id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $request->validate([
            'estado' => 'required|in:pendiente,en_revision,resuelto'
        ]);

        $reporte = Reporte::findOrFail($id);
        $reporte->update(['estado' => $request->estado]);

        return redirect()->route('admin.reportes.show', $reporte->id)
            ->with('success', 'Estado del reporte actualizado correctamente.');
    }

    // Métodos para Reseñas
    public function resenas()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $resenasProductosPendientes = ResenaProducto::with(['producto', 'usuario'])
            ->where('aprobado', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $resenasVendedoresPendientes = ResenaVendedor::with(['vendedor', 'usuario'])
            ->where('aprobado', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.resenas.index', compact('resenasProductosPendientes', 'resenasVendedoresPendientes'));
    }

    public function aprobarResenaProducto($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $resena = ResenaProducto::findOrFail($id);
        $resena->update(['aprobado' => true]);

        return redirect()->route('admin.resenas')->with('success', 'Reseña de producto aprobada.');
    }

    public function rechazarResenaProducto($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $resena = ResenaProducto::findOrFail($id);
        $resena->delete();

        return redirect()->route('admin.resenas')->with('success', 'Reseña de producto rechazada y eliminada.');
    }

    public function aprobarResenaVendedor($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $resena = ResenaVendedor::findOrFail($id);
        $resena->update(['aprobado' => true]);

        // Actualizar la calificación del vendedor
        $vendedor = $resena->vendedor;
        if ($vendedor->perfilVendedor) {
            $vendedor->perfilVendedor->actualizarCalificacion();
        }

        return redirect()->route('admin.resenas')->with('success', 'Reseña de vendedor aprobada.');
    }

    public function rechazarResenaVendedor($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $resena = ResenaVendedor::findOrFail($id);
        $resena->delete();

        return redirect()->route('admin.resenas')->with('success', 'Reseña de vendedor rechazada y eliminada.');
    }
    public function configuracion()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        return view('admin.configuracion.index');
    }
    // Métodos para Pedidos
    public function pedidos()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $pedidos = Pedido::with('usuario')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function mostrarPedido($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $pedido = Pedido::with(['usuario', 'detalles.producto.vendedor'])->findOrFail($id);
        
        return view('admin.pedidos.show', compact('pedido'));
    }

    public function actualizarPedido(Request $request, $id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,preparando,listo,entregado,cancelado'
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->update(['estado' => $request->estado]);

        return redirect()->route('admin.pedidos.show', $pedido->id)
            ->with('success', 'Estado del pedido actualizado correctamente.');
    }
    public function ventas()
    {
        // Verificar que es admin
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }

        // Obtener todas las ventas de la plataforma
        $ventas = VentaVendedor::with(['vendedor', 'pedido.usuario', 'producto'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener ventas personales del admin (vendedor_id = 1)
        $misVentas = VentaVendedor::where('vendedor_id', auth()->id())
            ->with(['pedido.usuario', 'producto'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener ventas de otros vendedores (excluyendo al admin)
        $ventasOtrosVendedores = $ventas->where('vendedor_id', '!=', auth()->id());

        // Estadísticas generales de la plataforma
        $estadisticas = [
            'total_ventas' => $ventas->count(),
            'ventas_pagadas' => $ventas->where('estado_pago', 'pagado')->count(),
            'ingresos_totales' => $ventas->where('estado_pago', 'pagado')->sum('total_vendedor'),
            'comisiones_totales' => $ventas->sum(function($venta) {
                return ($venta->precio_venta * $venta->cantidad * $venta->comision_porcentaje) / 100;
            })
        ];

        // Estadísticas personales del admin
        $misVentasEstadisticas = [
            'total_ventas' => $misVentas->count(),
            'ventas_pagadas' => $misVentas->where('estado_pago', 'pagado')->count(),
            'ingresos_totales' => $misVentas->where('estado_pago', 'pagado')->sum('total_vendedor')
        ];

        // Ventas agrupadas por vendedor (plataforma)
        $ventasPorVendedor = $ventas->groupBy('vendedor_id')->map(function($ventasVendedor) {
            $vendedor = $ventasVendedor->first()->vendedor;
            $ventasPagadas = $ventasVendedor->where('estado_pago', 'pagado');
            
            return (object)[
                'id' => $vendedor->id,
                'nombre' => $vendedor->nombre,
                'total_ventas' => $ventasVendedor->count(),
                'ventas_pagadas' => $ventasPagadas->count(),
                'total_ingresos' => $ventasPagadas->sum('total_vendedor'),
                'comisiones' => $ventasVendedor->sum(function($v) {
                    return ($v->precio_venta * $v->cantidad * $v->comision_porcentaje) / 100;
                })
            ];
        })->values();

        // Comisiones por mes (solo de otros vendedores)
        $comisionesPorMes = $ventasOtrosVendedores->groupBy(function($venta) {
            return $venta->created_at->format('Y-m');
        })->map(function($ventasMes, $key) {
            return (object)[
                'mes' => \Carbon\Carbon::parse($key)->format('m'),
                'anio' => \Carbon\Carbon::parse($key)->format('Y'),
                'total_ventas' => $ventasMes->count(),
                'total_comisiones' => $ventasMes->sum(function($v) {
                    return ($v->precio_venta * $v->cantidad * $v->comision_porcentaje) / 100;
                })
            ];
        })->values()->sortByDesc(function($item) {
            return $item->anio . $item->mes;
        });

        // Ventas personales agrupadas por producto
        $misVentasPorProducto = $misVentas->groupBy('producto_id')->map(function($ventasProducto) {
            $producto = $ventasProducto->first()->producto;
            $ventasPagadasProducto = $ventasProducto->where('estado_pago', 'pagado');
            
            return (object)[
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'unidad' => $producto->unidad,
                'total_ventas' => $ventasProducto->count(),
                'ventas_pagadas' => $ventasPagadasProducto->count(),
                'cantidad_total' => $ventasProducto->sum('cantidad'),
                'total_ingresos' => $ventasPagadasProducto->sum('total_vendedor')
            ];
        })->values();

        return view('admin.ventas.index', compact(
            'ventas', 
            'misVentas',
            'estadisticas', 
            'misVentasEstadisticas',
            'ventasPorVendedor', 
            'misVentasPorProducto',
            'comisionesPorMes'
        ));
    }
    public function mostrarVendedor($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $vendedor = VendedorPerfil::with(['usuario', 'resenas'])->findOrFail($id);

        return view('admin.vendedores.show', compact('vendedor'));
    }
    public function editarVendedor($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $vendedor = VendedorPerfil::with('usuario')->findOrFail($id);

        return view('admin.vendedores.edit', compact('vendedor'));
    }

    public function actualizarVendedor(Request $request, $id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $vendedor = VendedorPerfil::findOrFail($id);

        $request->validate([
            'descripcion' => 'required|string|min:50|max:1000',
            'direccion' => 'required|string|max:500',
            'metodos_entrega' => 'required|in:recogida,delivery,ambos',
            'horario_atencion' => 'nullable|string|max:255',
            'activo_vendedor' => 'boolean'
        ]);

        $vendedor->update([
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'metodos_entrega' => $request->metodos_entrega,
            'horario_atencion' => $request->horario_atencion,
            'activo_vendedor' => $request->has('activo_vendedor')
        ]);

        return redirect()->route('admin.vendedores.show', $vendedor->id)
            ->with('success', 'Vendedor actualizado correctamente.');
    }

    public function productosVendedor($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $vendedor = VendedorPerfil::with('usuario')->findOrFail($id);
        $productos = $vendedor->usuario->productos()->with('categoria')->get();

        return view('admin.vendedores.productos', compact('vendedor', 'productos'));
    }
}