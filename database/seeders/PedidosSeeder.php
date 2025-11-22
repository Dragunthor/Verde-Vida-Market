<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\VentaVendedor;
use Carbon\Carbon;

class PedidosSeeder extends Seeder
{
    public function run()
    {
        $clientes = Usuario::where('rol', 'cliente')->get();
        $productos = Producto::where('aprobado', true)->get();

        if ($clientes->isEmpty() || $productos->isEmpty()) {
            $this->command->error('No hay clientes o productos aprobados para crear pedidos!');
            return;
        }

        $estados = ['pendiente', 'confirmado', 'preparando', 'listo', 'entregado', 'cancelado'];
        $metodosPago = ['efectivo', 'transferencia', 'tarjeta'];

        // Crear 20 pedidos de ejemplo
        for ($i = 0; $i < 20; $i++) {
            $cliente = $clientes->random();
            $estado = $estados[array_rand($estados)];
            $metodoPago = $metodosPago[array_rand($metodosPago)];
            
            // Fecha aleatoria en los últimos 30 días
            $fechaPedido = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 24));

            $pedido = Pedido::create([
                'usuario_id' => $cliente->id,
                'estado' => $estado,
                'total' => 0, // Se calculará después
                'metodo_pago' => $metodoPago,
                'notas' => rand(0, 1) ? 'Por favor entregar antes de las 6pm' : null,
                'fecha_entrega' => $estado === 'entregado' ? $fechaPedido->copy()->addDays(2) : null,
                'created_at' => $fechaPedido,
                'updated_at' => $fechaPedido,
            ]);

            // Agregar productos al pedido
            $totalPedido = 0;
            $numProductos = rand(1, 5);
            $productosPedido = $productos->random($numProductos);

            foreach ($productosPedido as $producto) {
                $cantidad = rand(1, 3);
                $subtotal = $producto->precio * $cantidad;
                $totalPedido += $subtotal;

                // Crear detalle del pedido
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio' => $producto->precio,
                    'created_at' => $fechaPedido,
                    'updated_at' => $fechaPedido,
                ]);

                // Si el producto tiene vendedor, crear registro en ventas_vendedor
                if ($producto->vendedor_id) {
                    $comision = ($subtotal * 10) / 100; // 10% de comisión
                    $totalVendedor = $subtotal - $comision;

                    VentaVendedor::create([
                        'vendedor_id' => $producto->vendedor_id,
                        'pedido_id' => $pedido->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $cantidad,
                        'precio_venta' => $producto->precio,
                        'comision_porcentaje' => 10.00,
                        'total_vendedor' => $totalVendedor,
                        'estado_pago' => $estado === 'entregado' ? 'pagado' : 'pendiente',
                        'created_at' => $fechaPedido,
                        'updated_at' => $fechaPedido,
                    ]);
                }

                // Actualizar stock del producto
                $producto->decrement('stock', $cantidad);
            }

            // Actualizar total del pedido
            $pedido->update(['total' => $totalPedido]);
        }

        $this->command->info('Pedidos creados exitosamente!');
        $this->command->info('Total: 20 pedidos con diferentes estados');
    }
}