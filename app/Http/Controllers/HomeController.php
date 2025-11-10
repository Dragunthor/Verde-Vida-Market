<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\VendedorPerfil;

class HomeController extends Controller
{
    public function index()
    {
        $productosDestacados = Producto::with(['vendedor.perfilVendedor', 'categoria'])
            ->where('activo', true)
            ->where('aprobado', true)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $categorias = Categoria::withCount(['productos' => function($query) {
            $query->where('activo', true)->where('aprobado', true);
        }])->get();

        $vendedoresDestacados = VendedorPerfil::with('usuario')
            ->where('activo_vendedor', true)
            ->where('total_ventas', '>', 0)
            ->orderBy('calificacion_promedio', 'desc')
            ->take(5)
            ->get();

        return view('home', compact('productosDestacados', 'categorias', 'vendedoresDestacados'));
    }
}