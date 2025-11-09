@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fa fa-sign-in"></i> Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">Iniciar Sesión</button>
                </form>
                
                <div class="text-center mt-3">
                    <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
                </div>
                
                <!-- Cuenta de demostración -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6>Demo - Cuenta de Administrador:</h6>
                    <p class="mb-1"><strong>Email:</strong> admin@verdevida.com</p>
                    <p class="mb-0"><strong>Contraseña:</strong> cualquier contraseña</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection