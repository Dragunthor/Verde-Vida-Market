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

        // Perfil de vendedor para el admin
        VendedorPerfil::create([
            'usuario_id' => $admin->id,
            'descripcion' => 'Productos orgánicos de la más alta calidad, cultivados con amor y respeto por la naturaleza.',
            'direccion' => 'Av. Principal 123, Ciudad Orgánica',
            'metodos_entrega' => 'delivery',
            'horario_atencion' => 'Lunes a Viernes: 8:00 AM - 6:00 PM',
            'calificacion_promedio' => 4,
            'total_ventas' => 45,
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
                'activo' => true,
            ],
            [
                'nombre' => 'Carlos Rodríguez',
                'email' => 'carlos@ejemplo.com',
                'password' => Hash::make('cliente123'),
                'telefono' => '+1234567892',
                'direccion' => 'Av. Sol 789, Centro',
                'rol' => 'cliente',
                'activo' => true,
            ],
            [
                'nombre' => 'Ana Martínez',
                'email' => 'ana@ejemplo.com',
                'password' => Hash::make('cliente123'),
                'telefono' => '+1234567893',
                'direccion' => 'Calle Luna 321, Valle Verde',
                'rol' => 'cliente',
                'activo' => true,
            ],
            [
                'nombre' => 'Lucía Fernandez',
                'email' => 'lucia@ejemplo.com',
                'password' => Hash::make('cliente123'),
                'telefono' => '+1234567896',
                'direccion' => 'Jr. Primavera 234, Miraflores',
                'rol' => 'cliente',
                'activo' => true,
            ],
            [
                'nombre' => 'Roberto Silva',
                'email' => 'roberto@ejemplo.com',
                'password' => Hash::make('cliente123'),
                'telefono' => '+1234567897',
                'direccion' => 'Av. Mar 567, Costa Verde',
                'rol' => 'cliente',
                'activo' => true,
            ],
            [
                'nombre' => 'Elena Morales',
                'email' => 'elena@ejemplo.com',
                'password' => Hash::make('cliente123'),
                'telefono' => '+1234567898',
                'direccion' => 'Calle Los Pinos 890, San Isidro',
                'rol' => 'cliente',
                'activo' => true,
            ]
        ];

        foreach ($clientes as $cliente) {
            Usuario::create($cliente);
        }

        // Vendedores
        $vendedoresData = [
            [
                'nombre' => 'Don José Campesino',
                'email' => 'jose@ejemplo.com',
                'password' => Hash::make('vendedor123'),
                'telefono' => '+1234567894',
                'direccion' => 'Finca La Esperanza, Zona Rural, Huancayo',
                'rol' => 'vendedor',
                'activo' => true,
            ],
            [
                'nombre' => 'Granja Familiar Los Álamos',
                'email' => 'granja@ejemplo.com',
                'password' => Hash::make('vendedor123'),
                'telefono' => '+1234567895',
                'direccion' => 'Km 12 Carretera Norte, Trujillo',
                'rol' => 'vendedor',
                'activo' => true,
            ],
            [
                'nombre' => 'Doña Rosa Artesanal',
                'email' => 'rosa@ejemplo.com',
                'password' => Hash::make('vendedor123'),
                'telefono' => '+1234567899',
                'direccion' => 'Calle Artesanos 123, Cusco',
                'rol' => 'vendedor',
                'activo' => true,
            ],
            [
                'nombre' => 'Huerto Orgánico San Francisco',
                'email' => 'huerto@ejemplo.com',
                'password' => Hash::make('vendedor123'),
                'telefono' => '+1234567810',
                'direccion' => 'Mz A Lote 5, Urbanización San Francisco, Arequipa',
                'rol' => 'vendedor',
                'activo' => true,
            ],
            [
                'nombre' => 'Vivero Natural Life',
                'email' => 'vivero@ejemplo.com',
                'password' => Hash::make('vendedor123'),
                'telefono' => '+1234567811',
                'direccion' => 'Av. Ecológica 456, Lima Norte',
                'rol' => 'vendedor',
                'activo' => true,
            ]
        ];

        $vendedoresIds = [];
        foreach ($vendedoresData as $vendedor) {
            $usuario = Usuario::create($vendedor);
            $vendedoresIds[$vendedor['email']] = $usuario->id;
        }

        // Vendedor pendiente de aprobación
        $vendedorPendiente = Usuario::create([
            'nombre' => 'Agricultor Novato',
            'email' => 'novato@ejemplo.com',
            'password' => Hash::make('vendedor123'),
            'telefono' => '+1234567812',
            'direccion' => 'Carretera Central Km 45, Huancayo',
            'rol' => 'vendedor',
            'activo' => true,
        ]);

        // Crear perfiles de vendedor para TODOS los vendedores
        $perfilesVendedores = [
            [
                'usuario_id' => $vendedoresIds['jose@ejemplo.com'],
                'descripcion' => 'Más de 20 años cultivando la tierra con métodos tradicionales y respetuosos con el medio ambiente. Especialistas en papas nativas y maíz morado.',
                'direccion' => 'Finca La Esperanza, Zona Rural, Huancayo',
                'metodos_entrega' => 'delivery',
                'horario_atencion' => 'Lunes a Sábado: 6:00 AM - 4:00 PM',
                'calificacion_promedio' => 5,
                'total_ventas' => 120,
                'activo_vendedor' => true, // Vendedor aprobado
            ],
            [
                'usuario_id' => $vendedoresIds['granja@ejemplo.com'],
                'descripcion' => 'Granja familiar con tres generaciones dedicadas a la agricultura orgánica. Productos frescos directamente del campo a tu mesa.',
                'direccion' => 'Km 12 Carretera Norte, Trujillo',
                'metodos_entrega' => 'ambos',
                'horario_atencion' => 'Lunes a Domingo: 7:00 AM - 7:00 PM',
                'calificacion_promedio' => 4,
                'total_ventas' => 85,
                'activo_vendedor' => true, // Vendedor aprobado
            ],
            [
                'usuario_id' => $vendedoresIds['rosa@ejemplo.com'],
                'descripcion' => 'Elaboración artesanal de mermeladas, conservas y productos derivados de frutas naturales sin conservantes.',
                'direccion' => 'Calle Artesanos 123, Cusco',
                'metodos_entrega' => 'delivery',
                'horario_atencion' => 'Martes a Domingo: 9:00 AM - 5:00 PM',
                'calificacion_promedio' => 5,
                'total_ventas' => 65,
                'activo_vendedor' => true, // Vendedor aprobado
            ],
            [
                'usuario_id' => $vendedoresIds['huerto@ejemplo.com'],
                'descripcion' => 'Huerto urbano especializado en microvegetales y hierbas aromáticas cultivadas de forma 100% orgánica.',
                'direccion' => 'Mz A Lote 5, Urbanización San Francisco, Arequipa',
                'metodos_entrega' => 'recogida',
                'horario_atencion' => 'Lunes a Viernes: 8:00 AM - 6:00 PM',
                'calificacion_promedio' => 5,
                'total_ventas' => 42,
                'activo_vendedor' => true, // Vendedor aprobado
            ],
            [
                'usuario_id' => $vendedoresIds['vivero@ejemplo.com'],
                'descripcion' => 'Vivero especializado en plantas medicinales y aromáticas. También ofrecemos talleres de cultivo urbano.',
                'direccion' => 'Av. Ecológica 456, Lima Norte',
                'metodos_entrega' => 'ambos',
                'horario_atencion' => 'Lunes a Sábado: 8:30 AM - 6:30 PM',
                'calificacion_promedio' => 5,
                'total_ventas' => 78,
                'activo_vendedor' => true, // Vendedor aprobado
            ],
            [
                'usuario_id' => $vendedorPendiente->id,
                'descripcion' => 'Nuevo agricultor aprendiendo métodos orgánicos. Pequeña producción familiar con mucho amor.',
                'direccion' => 'Carretera Central Km 45, Huancayo',
                'metodos_entrega' => 'recogida',
                'horario_atencion' => 'Miércoles a Domingo: 8:00 AM - 3:00 PM',
                'calificacion_promedio' => 0,
                'total_ventas' => 0,
                'activo_vendedor' => false, // Vendedor pendiente de aprobación
            ]
        ];

        foreach ($perfilesVendedores as $perfil) {
            VendedorPerfil::create($perfil);
        }

        $this->command->info('Usuarios creados exitosamente!');
        $this->command->info('Admin: admin@verdevida.com / admin123');
        $this->command->info('Clientes: maria@ejemplo.com / cliente123');
        $this->command->info('Vendedor aprobado: jose@ejemplo.com / vendedor123');
        $this->command->info('Vendedor pendiente: novato@ejemplo.com / vendedor123');
        $this->command->info('Total vendedores aprobados: 6 (incluyendo admin)');
        $this->command->info('Total vendedores pendientes: 1');
    }
}
