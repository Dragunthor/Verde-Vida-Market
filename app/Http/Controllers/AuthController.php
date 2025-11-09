<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Simulación de login exitoso
        $credentials = $request->only('email', 'password');
        
        // Para demo, aceptamos cualquier credencial
        if (!empty($credentials['email']) && !empty($credentials['password'])) {
            // En una aplicación real, usaríamos Auth::attempt()
            session(['usuario' => [
                'id' => 1,
                'nombre' => 'Usuario Demo',
                'email' => $credentials['email'],
                'rol' => $credentials['email'] === 'admin@verdevida.com' ? 'admin' : 'cliente'
            ]]);
            
            if ($credentials['email'] === 'admin@verdevida.com') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('home');
        }
        
        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Simulación de registro exitoso
        $data = $request->validate([
            'nombre' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        // En una aplicación real, crearíamos el usuario en la base de datos
        session(['usuario' => [
            'id' => 2,
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'rol' => 'cliente'
        ]]);

        return redirect()->route('home')->with('success', '¡Registro exitoso! Bienvenido a VerdeVida Market.');
    }

    public function logout()
    {
        session()->forget('usuario');
        return redirect()->route('home');
    }
}