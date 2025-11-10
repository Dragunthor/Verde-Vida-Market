<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\CarritoTemporal;
use App\Models\Producto;
use App\Models\VentaVendedor;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::where('usuario_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pedidos.historial', compact('pedidos'));
    }

    public function show($id)
    {
        $pedido = Pedido::with(['detalles.producto.vendedor', 'usuario'])
            ->where('usuario_id', auth()->id())
            ->findOrFail($id);

        return view('pedidos.show', compact('pedido'));
    }

    public function checkout()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para finalizar la compra.');
        }

        $carrito = CarritoTemporal::with('producto.vendedor')
            ->where('usuario_id', auth()->id())
            ->get();

        if ($carrito->count() == 0) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        // Verificar stock de todos los productos
        foreach ($carrito as $item) {
            if ($item->producto->stock < $item->cantidad) {
                return redirect()->route('carrito.index')
                    ->with('error', "El producto '{$item->producto->nombre}' no tiene suficiente stock. Stock disponible: {$item->producto->stock}");
            }
        }

        $total = $carrito->sum(function($item) {
            return $item->producto->precio * $item->cantidad;
        });

        return view('pedidos.checkout', compact('carrito', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'direccion_entrega' => 'required|string|max:500',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta',
            'notas' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();

        try {
            // Obtener el carrito del usuario
            $carrito = CarritoTemporal::with('producto.vendedor')
                ->where('usuario_id', auth()->id())
                ->get();

            if ($carrito->count() == 0) {
                return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
            }

            // Verificar stock nuevamente antes de procesar
            foreach ($carrito as $item) {
                if ($item->producto->stock < $item->cantidad) {
                    throw new \Exception("El producto '{$item->producto->nombre}' no tiene suficiente stock.");
                }
            }

            // Calcular total
            $total = $carrito->sum(function($item) {
                return $item->producto->precio * $item->cantidad;
            });

            // Crear el pedido
            $pedido = Pedido::create([
                'usuario_id' => auth()->id(),
                'estado' => 'pendiente',
                'total' => $total,
                'metodo_pago' => $request->metodo_pago,
                'notas' => $request->notas,
                'fecha_entrega' => now()->addDays(2), // Entrega en 2 días
            ]);

            // Crear detalles del pedido y registrar ventas por vendedor
            foreach ($carrito as $item) {
                // Crear detalle del pedido
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item->producto_id,
                    'cantidad' => $item->cantidad,
                    'precio' => $item->producto->precio,
                ]);

                // Si el producto tiene vendedor, registrar en ventas_vendedor
                if ($item->producto->vendedor_id) {
                    $totalVenta = $item->producto->precio * $item->cantidad;
                    $comision = ($totalVenta * 10) / 100; // 10% de comisión
                    $totalVendedor = $totalVenta - $comision;

                    VentaVendedor::create([
                        'vendedor_id' => $item->producto->vendedor_id,
                        'pedido_id' => $pedido->id,
                        'producto_id' => $item->producto_id,
                        'cantidad' => $item->cantidad,
                        'precio_venta' => $item->producto->precio,
                        'comision_porcentaje' => 10.00,
                        'total_vendedor' => $totalVendedor,
                        'estado_pago' => 'pendiente',
                    ]);
                }

                // Actualizar stock del producto
                $producto = Producto::find($item->producto_id);
                $producto->decrement('stock', $item->cantidad);
            }

            // Vaciar el carrito
            CarritoTemporal::where('usuario_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('pedidos.confirmacion', $pedido->id)
                ->with('success', '¡Pedido realizado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('carrito.index')
                ->with('error', 'Ocurrió un error al procesar tu pedido: ' . $e->getMessage());
        }
    }

    public function confirmacion($id)
    {
        $pedido = Pedido::with(['detalles.producto.vendedor'])
            ->where('usuario_id', auth()->id())
            ->findOrFail($id);

        return view('pedidos.confirmacion', compact('pedido'));
    }

    public function cancelar($id)
    {
        DB::beginTransaction();

        try {
            $pedido = Pedido::with('detalles.producto')
                ->where('usuario_id', auth()->id())
                ->where('estado', 'pendiente')
                ->findOrFail($id);

            // Devolver stock de los productos
            foreach ($pedido->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
            }

            // Actualizar estado del pedido
            $pedido->update(['estado' => 'cancelado']);

            // Actualizar estado de las ventas de vendedores
            VentaVendedor::where('pedido_id', $pedido->id)
                ->update(['estado_pago' => 'cancelado']);

            DB::commit();

            return redirect()->route('pedidos.show', $pedido->id)
                ->with('success', 'Pedido cancelado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pedidos.show', $id)
                ->with('error', 'No se pudo cancelar el pedido.');
        }
    }
}