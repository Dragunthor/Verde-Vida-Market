<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('email', $credentials['email'])->first();

        if ($usuario && Hash::check($credentials['password'], $usuario->password)) {
            Auth::login($usuario);
            return redirect()->intended('/')->with('success', 'Bienvenido ' . $usuario->nombre);
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|min:6|confirmed',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string'
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'rol' => 'cliente'
        ]);

        Auth::login($usuario);

        return redirect('/')->with('success', 'Cuenta creada exitosamente. ¡Bienvenido a VerdeVida Market!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Sesión cerrada exitosamente.');
    }
}