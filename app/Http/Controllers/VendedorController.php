<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\VendedorPerfil;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\VentaVendedor;
use Illuminate\Support\Facades\Storage;

class VendedorController extends Controller
{
    // Verificación interna para vendedor activo
    private function verificarVendedorActivo()
    {
        if (!auth()->user()->esVendedor()) {
            return redirect('/')->with('error', 'Acceso no autorizado. Debes ser vendedor.');
        }

        $perfilVendedor = auth()->user()->perfilVendedor;
        
        if (!$perfilVendedor || !$perfilVendedor->activo_vendedor) {
            return redirect('/vendedor/solicitud')->with('error', 'Tu cuenta de vendedor está pendiente de aprobación.');
        }

        return null; // Todo está bien
    }

    public function solicitud()
    {
        if (auth()->user()->esVendedor()) {
            $perfilVendedor = auth()->user()->perfilVendedor;
            if ($perfilVendedor) {
                if ($perfilVendedor->activo_vendedor) {
                    return redirect('/vendedor/dashboard')->with('success', 'Ya eres un vendedor activo.');
                } else {
                    return view('vendedor.solicitud-pendiente', compact('perfilVendedor'));
                }
            }
        }

        return view('vendedor.solicitud');
    }

    public function enviarSolicitud(Request $request)
    {
        if (auth()->user()->esVendedor()) {
            return redirect('/vendedor/dashboard')->with('error', 'Ya eres vendedor.');
        }

        $request->validate([
            'descripcion' => 'required|string|min:10',
            'direccion' => 'required|string',
            'metodos_entrega' => 'required|in:recogida,delivery,ambos',
            'horario_atencion' => 'required|string'
        ]);

        // Cambiar rol a vendedor
        auth()->user()->update(['rol' => 'vendedor']);

        // Crear perfil de vendedor
        VendedorPerfil::create([
            'usuario_id' => auth()->id(),
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'metodos_entrega' => $request->metodos_entrega,
            'horario_atencion' => $request->horario_atencion,
            'activo_vendedor' => false // Pendiente de aprobación
        ]);

        return redirect('/vendedor/solicitud')->with('success', 'Solicitud enviada. Espera la aprobación del administrador.');
    }

    public function dashboard()
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $estadisticas = [
            'total_productos' => auth()->user()->productos()->count(),
            'productos_activos' => auth()->user()->productos()->where('activo', true)->count(),
            'productos_pendientes' => auth()->user()->productos()->where('aprobado', false)->count(),
            'total_ventas' => auth()->user()->ventasVendedor()->count(),
            'ventas_pendientes' => auth()->user()->ventasVendedor()->where('estado_pago', 'pendiente')->count(),
        ];

        $pedidosRecientes = auth()->user()->ventasVendedor()
            ->with('pedido')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('vendedor.dashboard', compact('estadisticas', 'pedidosRecientes'));
    }

    public function productos()
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $productos = auth()->user()->productos()->with('categoria')->get();
        return view('vendedor.productos.index', compact('productos'));
    }

    public function crearProducto()
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $categorias = \App\Models\Categoria::all();
        return view('vendedor.productos.crear', compact('categorias'));
    }

    public function guardarProducto(Request $request)
    {
        $verificacion = $this->verificarVendedorActivo();
        if ($verificacion) return $verificacion;

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'required|integer|min:0',
            'unidad' => 'required|string',
            'origen' => 'required|string',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $productoData = $request->except('imagen');
        $productoData['vendedor_id'] = auth()->id();
        $productoData['aprobado'] = false; // Requiere aprobación del admin

        if ($request->hasFile('imagen')) {
            $productoData['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($productoData);

        return redirect('/vendedor/productos')->with('success', 'Producto creado. Esperando aprobación del administrador.');
    }

    // ... más métodos para el vendedor
}