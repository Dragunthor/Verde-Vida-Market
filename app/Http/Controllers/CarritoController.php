<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarritoTemporal;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Usuario autenticado - carrito por usuario
            $carrito = CarritoTemporal::with('producto.vendedor.perfilVendedor')
                ->where('usuario_id', Auth::id())
                ->get();
        } else {
            // Usuario no autenticado - carrito por sesión
            $carrito = CarritoTemporal::with('producto.vendedor.perfilVendedor')
                ->where('sesion_id', session()->getId())
                ->get();
        }

        return view('carrito.index', compact('carrito'));
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Verificar stock
        if ($producto->stock < $request->cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        // Verificar que el producto esté activo y aprobado
        if (!$producto->activo || !$producto->aprobado) {
            return back()->with('error', 'Este producto no está disponible actualmente.');
        }

        // Buscar si el producto ya está en el carrito
        if (Auth::check()) {
            $itemCarrito = CarritoTemporal::where('usuario_id', Auth::id())
                ->where('producto_id', $request->producto_id)
                ->first();
        } else {
            $itemCarrito = CarritoTemporal::where('sesion_id', session()->getId())
                ->where('producto_id', $request->producto_id)
                ->first();
        }

        if ($itemCarrito) {
            // Actualizar cantidad si ya existe
            $nuevaCantidad = $itemCarrito->cantidad + $request->cantidad;
            
            if ($nuevaCantidad > $producto->stock) {
                return back()->with('error', 'No hay suficiente stock disponible.');
            }

            $itemCarrito->update(['cantidad' => $nuevaCantidad]);
        } else {
            // Crear nuevo item en el carrito
            CarritoTemporal::create([
                'sesion_id' => Auth::check() ? null : session()->getId(),
                'usuario_id' => Auth::check() ? Auth::id() : null,
                'producto_id' => $request->producto_id,
                'cantidad' => $request->cantidad
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $itemCarrito = CarritoTemporal::findOrFail($id);

        // Verificar que el usuario tiene permiso para modificar este item
        if (Auth::check()) {
            if ($itemCarrito->usuario_id !== Auth::id()) {
                return redirect()->route('carrito.index')->with('error', 'No tienes permiso para modificar este item.');
            }
        } else {
            if ($itemCarrito->sesion_id !== session()->getId()) {
                return redirect()->route('carrito.index')->with('error', 'No tienes permiso para modificar este item.');
            }
        }

        // Verificar stock
        if ($itemCarrito->producto->stock < $request->cantidad) {
            return redirect()->route('carrito.index')->with('error', 'No hay suficiente stock disponible.');
        }

        $itemCarrito->update(['cantidad' => $request->cantidad]);

        return redirect()->route('carrito.index')->with('success', 'Carrito actualizado.');
    }

    public function actualizarTodo(Request $request)
    {
        $request->validate([
            'cantidades' => 'required|array',
            'cantidades.*' => 'integer|min:0'
        ]);

        foreach ($request->cantidades as $itemId => $cantidad) {
            $itemCarrito = CarritoTemporal::find($itemId);
            
            if ($itemCarrito) {
                // Verificar permisos
                if (Auth::check()) {
                    if ($itemCarrito->usuario_id !== Auth::id()) continue;
                } else {
                    if ($itemCarrito->sesion_id !== session()->getId()) continue;
                }

                if ($cantidad == 0) {
                    // Eliminar item si cantidad es 0
                    $itemCarrito->delete();
                } else {
                    // Verificar stock
                    if ($itemCarrito->producto->stock >= $cantidad) {
                        $itemCarrito->update(['cantidad' => $cantidad]);
                    }
                }
            }
        }

        return redirect()->route('carrito.index')->with('success', 'Carrito actualizado.');
    }

    public function eliminar($id)
    {
        $itemCarrito = CarritoTemporal::findOrFail($id);

        // Verificar permisos
        if (Auth::check()) {
            if ($itemCarrito->usuario_id !== Auth::id()) {
                return redirect()->route('carrito.index')->with('error', 'No tienes permiso para eliminar este item.');
            }
        } else {
            if ($itemCarrito->sesion_id !== session()->getId()) {
                return redirect()->route('carrito.index')->with('error', 'No tienes permiso para eliminar este item.');
            }
        }

        $itemCarrito->delete();

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        if (Auth::check()) {
            CarritoTemporal::where('usuario_id', Auth::id())->delete();
        } else {
            CarritoTemporal::where('sesion_id', session()->getId())->delete();
        }

        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado.');
    }
}