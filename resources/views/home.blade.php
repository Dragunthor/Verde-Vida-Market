@extends('layouts.app')

@section('title', 'Inicio - VerdeVida Market')

@section('content')
<div class="hero-section bg-success text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="display-4">Bienvenido a VerdeVida Market</h1>
        <p class="lead">Productos orgánicos frescos directamente del campo a tu mesa</p>
        <a href="{{ route('productos.index') }}" class="btn btn-light btn-lg mt-3">Ver Productos</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <h2>Productos Destacados</h2>
        <div class="row">
            @foreach($productosDestacados as $producto)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('images/placeholder.jpg') }}" 
                         class="card-img-top" alt="{{ $producto->nombre }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($producto->descripcion, 100) }}</p>
                        
                        @if($producto->vendedor && $producto->vendedor->perfilVendedor)
                            <small class="text-muted mb-2">
                                Vendido por: {{ $producto->vendedor->nombre }}
                            </small>
                        @endif
                        
                        <p class="text-success fw-bold h5 mb-2">S/ {{ number_format($producto->precio, 2) }}</p>
                        
                        @if($producto->estaDisponible())
                            <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-success mt-auto">Ver Detalles</a>
                        @else
                            <button class="btn btn-secondary mt-auto" disabled>No Disponible</button>
                        @endif
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
            <a href="{{ route('productos.index', ['categoria' => $categoria->id]) }}" 
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                {{ $categoria->nombre }}
                <span class="badge bg-success rounded-pill">{{ $categoria->productosActivos()->count() }}</span>
            </a>
            @endforeach
        </div>
        
        <div class="mt-4">
            <h4>Vendedores Destacados</h4>
            <div class="list-group">
                @foreach($vendedoresDestacados as $vendedor)
                <a href="{{ route('vendedores.show', $vendedor->usuario_id) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $vendedor->usuario->nombre }}</h6>
                        <small>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $vendedor->calificacion_promedio)
                                    <i class="fa fa-star text-warning"></i>
                                @else
                                    <i class="fa fa-star-o text-muted"></i>
                                @endif
                            @endfor
                        </small>
                    </div>
                    <small class="text-muted">{{ $vendedor->total_ventas }} ventas</small>
                </a>
                @endforeach
            </div>
        </div>

        <div class="mt-4">
            <h4>¿Por qué elegirnos?</h4>
            <ul class="list-unstyled">
                <li><i class="fa fa-check text-success"></i> Productos 100% orgánicos</li>
                <li><i class="fa fa-check text-success"></i> Cultivo local y sostenible</li>
                <li><i class="fa fa-check text-success"></i> Apoyo a productores locales</li>
                <li><i class="fa fa-check text-success"></i> Entregas a domicilio</li>
                <li><i class="fa fa-check text-success"></i> Precios justos</li>
            </ul>
        </div>
    </div>
</div>
@endsection