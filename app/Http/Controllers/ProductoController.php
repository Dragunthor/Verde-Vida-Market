<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Usuario;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'vendedor.perfilVendedor'])
            ->where('activo', true)
            ->where('aprobado', true);

        // Filtro por búsqueda
        if ($request->has('busqueda') && $request->busqueda != '') {
            $query->where('nombre', 'like', '%' . $request->busqueda . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->busqueda . '%');
        }

        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria_id', $request->categoria);
        }

        // Filtro por vendedor
        if ($request->has('vendedor') && $request->vendedor != '') {
            $query->where('vendedor_id', $request->vendedor);
        }

        $productos = $query->orderBy('created_at', 'desc')->paginate(12);
        $categorias = Categoria::all();
        $vendedores = Usuario::where('rol', 'vendedor')
            ->whereHas('perfilVendedor', function($q) {
                $q->where('activo_vendedor', true);
            })->get();

        return view('productos.catalog', compact('productos', 'categorias', 'vendedores'));
    }

    public function show($id)
    {
        $producto = Producto::with([
            'categoria', 
            'vendedor.perfilVendedor',
            'resenas.usuario'
        ])->findOrFail($id);

        // Productos relacionados (misma categoría, excluyendo el actual)
        $productosRelacionados = Producto::with('vendedor.perfilVendedor')
            ->where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->where('activo', true)
            ->where('aprobado', true)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('productos.show', compact('producto', 'productosRelacionados'));
    }
}