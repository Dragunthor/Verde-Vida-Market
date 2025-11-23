<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ResenaProducto;
use App\Models\ResenaVendedor;
use App\Models\Producto;
use App\Models\Usuario;

class ResenaController extends Controller
{
    public function storeProducto(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|min:10|max:500'
        ]);

        $usuario = Auth::user();
        $producto = Producto::findOrFail($request->producto_id);

        // Verificar que el usuario puede calificar este producto
        if (!$usuario->puedeCalificarProducto($producto->id)) {
            return redirect()->back()->with('error', 'No puedes calificar este producto o ya lo has calificado.');
        }

        // Crear la reseña (pendiente de aprobación por defecto)
        ResenaProducto::create([
            'producto_id' => $producto->id,
            'usuario_id' => $usuario->id,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'aprobado' => false // Requiere aprobación del administrador
        ]);

        return redirect()->back()->with('success', '¡Reseña enviada! Será publicada después de la aprobación del administrador.');
    }

    public function storeVendedor(Request $request)
    {
        $request->validate([
            'vendedor_id' => 'required|exists:usuarios,id',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|min:10|max:500'
        ]);

        $usuario = Auth::user();
        $vendedor = Usuario::findOrFail($request->vendedor_id);

        // Verificar que el usuario puede calificar este vendedor
        if (!$usuario->puedeCalificarVendedor($vendedor->id)) {
            return redirect()->back()->with('error', 'No puedes calificar este vendedor o ya lo has calificado.');
        }

        // Crear la reseña (pendiente de aprobación por defecto)
        ResenaVendedor::create([
            'vendedor_id' => $vendedor->id,
            'usuario_id' => $usuario->id,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'aprobado' => false // Requiere aprobación del administrador
        ]);

        return redirect()->back()->with('success', '¡Reseña enviada! Será publicada después de la aprobación del administrador.');
    }

    public function misResenas()
    {
        $usuario = Auth::user();
        
        $resenasProductos = $usuario->resenasProductos()->with('producto')->get();
        $resenasVendedores = $usuario->resenasVendedores()->with('vendedor')->get();

        return view('resenas.mis-resenas', compact('resenasProductos', 'resenasVendedores'));
    }
}