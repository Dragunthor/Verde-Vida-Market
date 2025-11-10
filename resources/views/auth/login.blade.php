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
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    @if(request()->has('redirect'))
                        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    @endif
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 mb-3">Iniciar Sesión</button>
                </form>
                
                <div class="text-center mt-3">
                    <p>¿No tienes cuenta? <a href="{{ route('register') }}" class="text-success">Regístrate aquí</a></p>
                </div>
                
                <!-- Cuentas de demostración -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="mb-3">Cuentas de Demo:</h6>
                    
                    <div class="mb-2">
                        <strong>Administrador:</strong>
                        <div class="d-flex justify-content-between">
                            <span>admin@verdevida.com</span>
                            <span class="text-muted">admin123</span>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <strong>Cliente:</strong>
                        <div class="d-flex justify-content-between">
                            <span>maria@ejemplo.com</span>
                            <span class="text-muted">cliente123</span>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <strong>Vendedor:</strong>
                        <div class="d-flex justify-content-between">
                            <span>jose@ejemplo.com</span>
                            <span class="text-muted">vendedor123</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection