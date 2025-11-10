<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\VendedorPerfil;
use App\Models\Pedido;

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

    public function dashboard()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $estadisticas = [
            'total_usuarios' => Usuario::count(),
            'total_vendedores' => Usuario::where('rol', 'vendedor')->count(),
            'total_productos' => Producto::count(),
            'productos_pendientes' => Producto::where('aprobado', false)->count(),
            'vendedores_pendientes' => VendedorPerfil::where('activo_vendedor', false)->count(),
            'total_pedidos' => Pedido::count(),
        ];

        $productosRecientes = Producto::with('vendedor', 'categoria')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('estadisticas', 'productosRecientes'));
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