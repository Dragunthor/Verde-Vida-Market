@extends('layouts.app')

@section('title', 'Inicio - VerdeVida Market')

@section('content')
<div class="hero-section bg-success text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="display-4">Bienvenido a VerdeVida Market</h1>
        <p class="lead">Productos orgánicos frescos directamente del campo a tu mesa</p>
        <a href="{{ route('productos.catalog') }}" class="btn btn-light btn-lg mt-3">Ver Productos</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <h2>Productos Destacados</h2>
        <div class="row">
            @foreach($productosDestacados as $producto)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('images/' . $producto['imagen']) }}" class="card-img-top" alt="{{ $producto['nombre'] }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $producto['nombre'] }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($producto['descripcion'], 100) }}</p>
                        <p class="text-success fw-bold h5 mb-2">S/ {{ number_format($producto['precio'], 2) }}</p>
                        <a href="{{ route('productos.show', $producto['id']) }}" class="btn btn-success mt-auto">Ver Detalles</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="col-md-4">
        <h3>Categorías</h3>
        <div class="list-group">
            @foreach($categorias as $categoria)
            <a href="{{ route('productos.catalog', ['categoria' => $categoria['id']]) }}" class="list-group-item list-group-item-action">
                {{ $categoria['nombre'] }}
            </a>
            @endforeach
        </div>
        
        <div class="mt-4">
            <h4>¿Por qué elegirnos?</h4>
            <ul class="list-unstyled">
                <li><i class="fa fa-check text-success"></i> Productos 100% orgánicos</li>
                <li><i class="fa fa-check text-success"></i> Cultivo local y sostenible</li>
                <li><i class="fa fa-check text-success"></i> Entregas a domicilio</li>
                <li><i class="fa fa-check text-success"></i> Precios justos</li>
            </ul>
        </div>
    </div>
</div>
@endsection