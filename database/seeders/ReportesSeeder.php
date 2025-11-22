<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reporte;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Pedido;
use Carbon\Carbon;

class ReportesSeeder extends Seeder
{
    public function run()
    {
        $usuarios = Usuario::where('rol', 'cliente')->get();
        $productos = Producto::all();
        $pedidos = Pedido::all();

        $tipos = ['producto', 'vendedor', 'pedido', 'tecnico'];
        $estados = ['pendiente', 'en_revision', 'resuelto'];

        $reportes = [
            [
                'titulo' => 'Producto con imagen incorrecta',
                'descripcion' => 'La imagen del producto no corresponde a lo que se muestra en la descripción.',
                'tipo' => 'producto',
                'estado' => 'pendiente',
            ],
            [
                'titulo' => 'Problema con la entrega',
                'descripcion' => 'El pedido llegó con retraso y algunos productos en mal estado.',
                'tipo' => 'pedido',
                'estado' => 'en_revision',
            ],
            [
                'titulo' => 'Vendedor no responde',
                'descripcion' => 'He intentado contactar al vendedor pero no responde mis mensajes.',
                'tipo' => 'vendedor',
                'estado' => 'pendiente',
            ],
            [
                'titulo' => 'Error en el sistema de pagos',
                'descripcion' => 'No puedo completar mi compra, el sistema muestra error al procesar el pago.',
                'tipo' => 'tecnico',
                'estado' => 'resuelto',
            ],
            [
                'titulo' => 'Producto agotado sigue apareciendo',
                'descripcion' => 'Un producto que muestra agotado aún permite agregarlo al carrito.',
                'tipo' => 'producto',
                'estado' => 'en_revision',
            ],
        ];

        foreach ($reportes as $reporteData) {
            $usuario = $usuarios->random();
            
            // Asignar objeto_id según el tipo
            switch ($reporteData['tipo']) {
                case 'producto':
                    $objetoId = $productos->random()->id;
                    break;
                case 'pedido':
                    $objetoId = $pedidos->random()->id;
                    break;
                case 'vendedor':
                    $objetoId = Usuario::where('rol', 'vendedor')->inRandomOrder()->first()->id;
                    break;
                default:
                    $objetoId = 1; // Para técnico
            }

            Reporte::create([
                'usuario_id' => $usuario->id,
                'tipo' => $reporteData['tipo'],
                'objeto_id' => $objetoId,
                'titulo' => $reporteData['titulo'],
                'descripcion' => $reporteData['descripcion'],
                'estado' => $reporteData['estado'],
                'created_at' => Carbon::now()->subDays(rand(0, 15)),
            ]);
        }

        $this->command->info('Reportes creados exitosamente!');
        $this->command->info('Total: ' . count($reportes) . ' reportes con diferentes estados');
    }
}