<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResenaProducto;
use App\Models\ResenaVendedor;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Pedido;
use Carbon\Carbon;

class ResenasSeeder extends Seeder
{
    public function run()
    {
        $clientes = Usuario::where('rol', 'cliente')->get();
        $productos = Producto::where('aprobado', true)->get();
        $pedidosEntregados = Pedido::where('estado', 'entregado')->get();

        // Reseñas de productos - solo para pedidos entregados
        $comentariosProductos = [
            'Excelente calidad, muy fresco y delicioso.',
            'Llegó en perfecto estado, muy satisfecho con la compra.',
            'Buen producto pero un poco caro para lo que es.',
            'Increíble sabor, definitivamente volveré a comprar.',
            'No cumplió con mis expectativas, esperaba algo mejor.',
            'Producto de primera calidad, super recomendado.',
            'Buen sabor pero la presentación podría mejorar.',
            'Totalmente orgánico, se nota la diferencia.',
            'Rápida entrega y producto en perfecto estado.',
            'Calidad premium, vale cada sol invertido.',
        ];

        foreach ($productos->take(25) as $producto) {
            $cliente = $clientes->random();
            
            // Buscar un pedido entregado que contenga este producto
            $pedido = $pedidosEntregados->filter(function($pedido) use ($producto) {
                return $pedido->detalles->contains('producto_id', $producto->id);
            })->first();

            // Si no hay pedido con este producto, saltar
            if (!$pedido) continue;

            // Verificar que el cliente no haya reseñado ya este producto
            $yaResenado = ResenaProducto::where('producto_id', $producto->id)
                ->where('usuario_id', $cliente->id)
                ->exists();
                
            if (!$yaResenado) {
                ResenaProducto::create([
                    'producto_id' => $producto->id,
                    'usuario_id' => $cliente->id,
                    'pedido_id' => $pedido->id,
                    'calificacion' => rand(3, 5),
                    'comentario' => $comentariosProductos[array_rand($comentariosProductos)],
                    'aprobado' => rand(0, 1), // Algunas pendientes de aprobación
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // Reseñas de vendedores - solo para pedidos entregados
        $vendedores = Usuario::where('rol', 'vendedor')
            ->whereHas('perfilVendedor', function($query) {
                $query->where('activo_vendedor', true);
            })->get();

        $comentariosVendedores = [
            'Excelente atención, muy profesional.',
            'Productos de calidad y buen servicio.',
            'Respondió rápidamente a mis consultas.',
            'Buena comunicación durante todo el proceso.',
            'Vendedor confiable, recomiendo totalmente.',
            'Podría mejorar los tiempos de respuesta.',
            'Muy amable y servicial.',
            'Productos exactamente como en la descripción.',
            'Entrega puntual y productos frescos.',
            'Experiencia positiva, volveré a comprar.',
        ];

        foreach ($vendedores as $vendedor) {
            $cliente = $clientes->random();
            
            // Buscar un pedido entregado que contenga productos de este vendedor
            $pedido = $pedidosEntregados->filter(function($pedido) use ($vendedor) {
                return $pedido->detalles->contains('producto.vendedor_id', $vendedor->id);
            })->first();

            // Si no hay pedido con productos de este vendedor, saltar
            if (!$pedido) continue;

            // Verificar que el cliente no haya reseñado ya este vendedor
            $yaResenado = ResenaVendedor::where('vendedor_id', $vendedor->id)
                ->where('usuario_id', $cliente->id)
                ->exists();
                
            if (!$yaResenado) {
                ResenaVendedor::create([
                    'vendedor_id' => $vendedor->id,
                    'usuario_id' => $cliente->id,
                    'pedido_id' => $pedido->id,
                    'calificacion' => rand(3, 5),
                    'comentario' => $comentariosVendedores[array_rand($comentariosVendedores)],
                    'aprobado' => rand(0, 1), // Algunas pendientes de aprobación
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }

        $this->command->info('Reseñas creadas exitosamente!');
        $this->command->info('Reseñas de productos: ' . ResenaProducto::count());
        $this->command->info('Reseñas de vendedores: ' . ResenaVendedor::count());
        $this->command->info('Algunas reseñas están pendientes de aprobación');
    }
}
