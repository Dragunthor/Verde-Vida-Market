<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\VendedorPerfil;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        // Usuario Administrador
        $admin = Usuario::create([
            'nombre' => 'Administrador VerdeVida',
            'email' => 'admin@verdevida.com',
            'password' => Hash::make('admin123'),
            'telefono' => '+1234567890',
            'direccion' => 'Av. Principal 123, Ciudad Orgánica',
            'rol' => 'admin',
            'activo' => true,
        ]);

        // Vendedor Principal (Admin también como vendedor inicial)
        $vendedorPerfil = VendedorPerfil::create([
            'usuario_id' => $admin->id,
            'descripcion' => 'Productos orgánicos de la más alta calidad, cultivados con amor y respeto por la naturaleza.',
            'direccion' => 'Av. Principal 123, Ciudad Orgánica',
            'metodos_entrega' => 'ambos',
            'horario_atencion' => 'Lunes a Viernes: 8:00 AM - 6:00 PM',
            'activo_vendedor' => true,
        ]);

        // Clientes de ejemplo
        $clientes = [
            [
                'nombre' => 'María González',
                'email' => 'maria@ejemplo.com',
                'password' => Hash::make('cliente123'),
                'telefono' => '+1234567891',
                'direccion' => 'Calle Flores 456, Jardín Norte',
                'rol' => 'cliente',
            ],
            [
                'nombre' => 'Carlos Rodríguez',
                'email' => 'carlos@ejemplo.com',
                'password' => Hash::make('cliente123'),
                'telefono' => '+1234567892',
                'direccion' => 'Av. Sol 789, Centro',
                'rol' => 'cliente',
            ],
            [
                'nombre' => 'Ana Martínez',
                'email' => 'ana@ejemplo.com',
                'password' => Hash::make('cliente123'),
                'telefono' => '+1234567893',
                'direccion' => 'Calle Luna 321, Valle Verde',
                'rol' => 'cliente',
            ]
        ];

        foreach ($clientes as $cliente) {
            Usuario::create($cliente);
        }

        // Vendedores adicionales (inactivos por ahora)
        $vendedores = [
            [
                'nombre' => 'Don José Campesino',
                'email' => 'jose@ejemplo.com',
                'password' => Hash::make('vendedor123'),
                'telefono' => '+1234567894',
                'direccion' => 'Finca La Esperanza, Zona Rural',
                'rol' => 'vendedor',
            ],
            [
                'nombre' => 'Granja Familiar Los Álamos',
                'email' => 'granja@ejemplo.com',
                'password' => Hash::make('vendedor123'),
                'telefono' => '+1234567895',
                'direccion' => 'Km 12 Carretera Norte',
                'rol' => 'vendedor',
            ]
        ];

        foreach ($vendedores as $vendedor) {
            Usuario::create($vendedor);
        }

        $this->command->info('Usuarios creados exitosamente!');
        $this->command->info('Admin: admin@verdevida.com / admin123');
        $this->command->info('Clientes: maria@ejemplo.com / cliente123');
    }
}