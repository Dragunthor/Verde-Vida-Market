<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\VendedorPerfil;
use App\Models\Pedido;
use App\Models\Reporte;
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
}