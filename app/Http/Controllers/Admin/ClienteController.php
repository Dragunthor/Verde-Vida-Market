<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    // Verificación interna para admin
    private function verificarAdmin()
    {
        if (!auth()->user()->esAdmin()) {
            return redirect('/')->with('error', 'Acceso no autorizado. Debes ser administrador.');
        }
        return null;
    }

    public function index()
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $clientes = Usuario::where('rol', 'cliente')
            ->withCount(['pedidos as total_pedidos'])
            ->withSum(['pedidos as total_compras'], 'total')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($cliente) {
                return [
                    'id' => $cliente->id,
                    'nombre' => $cliente->nombre,
                    'email' => $cliente->email,
                    'telefono' => $cliente->telefono,
                    'fecha_registro' => $cliente->created_at->format('d/m/Y'),
                    'total_pedidos' => $cliente->total_pedidos,
                    'total_compras' => $cliente->total_compras ?? 0,
                    'activo' => $cliente->activo,
                    'direccion' => $cliente->direccion
                ];
            });

        return view('admin.clientes.index', compact('clientes'));
    }

    public function show($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $cliente = Usuario::with(['pedidos' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        $estadisticas = [
            'total_pedidos' => $cliente->pedidos->count(),
            'pedidos_entregados' => $cliente->pedidos->where('estado', 'entregado')->count(),
            'total_gastado' => $cliente->pedidos->where('estado', 'entregado')->sum('total'),
            'pedidos_pendientes' => $cliente->pedidos->whereIn('estado', ['pendiente', 'confirmado', 'preparando'])->count(),
            'ultimo_pedido' => $cliente->pedidos->first() ? $cliente->pedidos->first()->created_at->format('d/m/Y') : 'Nunca',
        ];

        return view('admin.clientes.show', compact('cliente', 'estadisticas'));
    }

    public function edit($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $cliente = Usuario::findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $cliente = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $cliente->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'activo' => 'boolean',
        ]);

        $cliente->update([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'activo' => $request->has('activo'),
        ]);

        return redirect()->route('admin.clientes.show', $cliente->id)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function toggleActivo($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $cliente = Usuario::findOrFail($id);
        $cliente->update(['activo' => !$cliente->activo]);

        $estado = $cliente->activo ? 'activada' : 'desactivada';
        return redirect()->back()->with('success', "Cuenta {$estado} correctamente.");
    }

    public function historialPedidos($id)
    {
        $verificacion = $this->verificarAdmin();
        if ($verificacion) return $verificacion;

        $cliente = Usuario::findOrFail($id);
        $pedidos = Pedido::where('usuario_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.clientes.historial-pedidos', compact('cliente', 'pedidos'));
    }
}