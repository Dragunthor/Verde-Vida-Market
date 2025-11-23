<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuario;
use App\Models\VendedorPerfil;
use App\Models\Producto;

class PerfilController extends Controller
{
    public function edit()
    {
        $usuario = Auth::user();
        
        // Determinar qué layout usar basado en la ruta o el rol
        if (request()->is('vendedor/*') || $this->esVendedorAprobado($usuario)) {
            return view('vendedor.perfil.edit', compact('usuario'));
        }
        
        return view('perfil.edit', compact('usuario'));
    }

    // Método auxiliar para verificar vendedor aprobado
    private function esVendedorAprobado($usuario)
    {
        return $usuario->rol === 'vendedor' && 
            $usuario->perfilVendedor && 
            $usuario->perfilVendedor->activo_vendedor === true;
    }

    public function update(Request $request)
    {
        $usuario = Auth::user();

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $usuario->update($request->only(['nombre', 'email', 'telefono', 'direccion']));

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }

    // NUEVO MÉTODO: Actualizar información de vendedor
    public function updateVendedor(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario->esVendedor()) {
            return redirect()->back()->with('error', 'No eres vendedor.');
        }

        $perfilVendedor = $usuario->perfilVendedor;

        if (!$perfilVendedor) {
            return redirect()->back()->with('error', 'No tienes perfil de vendedor.');
        }

        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|string|min:50|max:1000',
            'direccion_operacion' => 'required|string|max:500',
            'metodos_entrega' => 'required|in:recogida,delivery,ambos',
            'horario_atencion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $perfilVendedor->update([
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion_operacion,
            'metodos_entrega' => $request->metodos_entrega,
            'horario_atencion' => $request->horario_atencion,
        ]);

        return redirect()->back()->with('success', 'Información de tienda actualizada correctamente.');
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $usuario = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $usuario->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        // Actualizar contraseña
        $usuario->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Contraseña cambiada correctamente.');
    }

    public function cancelarSolicitud()
    {
        $usuario = Auth::user();

        if (!$usuario->esVendedor()) {
            return redirect()->back()->with('error', 'No tienes una solicitud de vendedor pendiente.');
        }

        $perfilVendedor = $usuario->perfilVendedor;

        if (!$perfilVendedor) {
            return redirect()->back()->with('error', 'No tienes perfil de vendedor.');
        }

        // Solo permitir cancelar si está pendiente
        if ($perfilVendedor->activo_vendedor) {
            return redirect()->back()->with('error', 'No puedes cancelar una cuenta de vendedor activa. Usa la opción "Dejar de ser vendedor".');
        }

        // Eliminar perfil de vendedor
        $perfilVendedor->delete();

        // Cambiar rol a cliente
        $usuario->update(['rol' => 'cliente']);

        return redirect()->route('perfil.edit')->with('success', 'Solicitud de vendedor cancelada correctamente.');
    }

    public function dejarVendedor()
    {
        $usuario = Auth::user();

        if (!$usuario->esVendedor()) {
            return redirect()->back()->with('error', 'No eres vendedor.');
        }

        // Obtener el perfil de vendedor
        $perfilVendedor = $usuario->perfilVendedor;

        if ($perfilVendedor) {
            // Desactivar productos del vendedor
            Producto::where('vendedor_id', $usuario->id)->update(['activo' => false]);
            
            // Eliminar perfil de vendedor
            $perfilVendedor->delete();
        }

        // Cambiar rol a cliente
        $usuario->update(['rol' => 'cliente']);

        return redirect()->route('perfil.edit')->with('success', 'Has dejado de ser vendedor. Tus productos han sido desactivados.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'confirmacion' => 'required|in:ELIMINAR'
        ]);

        $usuario = Auth::user();

        // Verificar que confirmó correctamente
        if ($request->confirmacion !== 'ELIMINAR') {
            return redirect()->back()->with('error', 'Confirmación incorrecta.');
        }

        // Si es vendedor, desactivar productos primero
        if ($usuario->esVendedor()) {
            Producto::where('vendedor_id', $usuario->id)->update(['activo' => false]);
            
            // Eliminar perfil de vendedor si existe
            if ($usuario->perfilVendedor) {
                $usuario->perfilVendedor->delete();
            }
        }

        // Cerrar sesión
        Auth::logout();

        // Eliminar usuario
        $usuario->delete();

        // Invalidar sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada permanentemente.');
    }
}