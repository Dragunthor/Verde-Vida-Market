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

        // Reseñas de productos
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

        foreach ($productos->take(15) as $producto) {
            $cliente = $clientes->random();
            $pedido = $pedidosEntregados->random();

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

        // Reseñas de vendedores
        $vendedores = Usuario::where('rol', 'vendedor')->get();
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
            $pedido = $pedidosEntregados->random();

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

        $this->command->info('Reseñas creadas exitosamente!');
        $this->command->info('Reseñas de productos: 15');
        $this->command->info('Reseñas de vendedores: ' . $vendedores->count());
        $this->command->info('Algunas reseñas están pendientes de aprobación');
    }
}