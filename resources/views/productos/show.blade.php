@extends('layouts.app')

@section('title', $producto['nombre'])

@section('content')
<!-- Migas de pan -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.catalog') }}">Productos</a></li>
        <li class="breadcrumb-item active">{{ $producto['nombre'] }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-6">
        <div class="product-image">
            <img src="{{ asset('images/' . $producto['imagen']) }}" 
                 class="img-fluid rounded" alt="{{ $producto['nombre'] }}">
        </div>
    </div>
    
    <div class="col-md-6">
        <h1 class="product-title">{{ $producto['nombre'] }}</h1>
        
        <div class="product-meta mb-3">
            <span class="badge bg-success">{{ $producto['categoria_nombre'] }}</span>
            @if($producto['origen'] == 'Local')
                <span class="badge bg-info">Producto Local</span>
            @endif
        </div>
        
        <div class="product-price mb-4">
            <h2 class="text-success">S/ {{ number_format($producto['precio'], 2) }}</h2>
            <small class="text-muted">por {{ $producto['unidad'] }}</small>
        </div>
        
        <div class="product-info mb-4">
            <h5>Descripción</h5>
            <p class="text-muted">{!! nl2br(e($producto['descripcion'])) !!}</p>
        </div>
        
        <div class="product-details mb-4">
            <div class="row">
                <div class="col-6">
                    <strong>Stock disponible:</strong><br>
                    <span class="{{ $producto['stock'] > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $producto['stock'] }} {{ $producto['unidad'] }}
                    </span>
                </div>
                <div class="col-6">
                    <strong>Origen:</strong><br>
                    {{ $producto['origen'] }}
                </div>
            </div>
        </div>
        
        @if($producto['stock'] > 0)
            <form method="POST" action="{{ route('carrito.agregar') }}" class="mb-4">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto['id'] }}">
                
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label for="cantidad" class="form-label"><strong>Cantidad:</strong></label>
                    </div>
                    <div class="col-auto">
                        <input type="number" class="form-control" id="cantidad" name="cantidad" 
                               value="1" min="1" max="{{ $producto['stock'] }}" 
                               style="width: 100px;">
                    </div>
                    <div class="col-auto">
                        <span class="text-muted">{{ $producto['unidad'] }}</span>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fa fa-cart-plus"></i> Agregar al Carrito
                    </button>
                </div>
            </form>
        @else
            <div class="alert alert-warning">
                <h5><i class="fa fa-exclamation-triangle"></i> Producto Agotado</h5>
                <p class="mb-0">Este producto no está disponible temporalmente.</p>
            </div>
        @endif
        
        <div class="product-actions">
            <a href="{{ route('productos.catalog', ['categoria' => $producto['categoria_id']]) }}" 
               class="btn btn-outline-success w-100">
                <i class="fa fa-arrow-left"></i> Ver más productos de {{ $producto['categoria_nombre'] }}
            </a>
        </div>
    </div>
</div>

<!-- Productos relacionados -->
@if(count($relacionados) > 0)
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-4">Productos Relacionados</h3>
        <div class="row">
            @foreach($relacionados as $relacionado)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('images/' . $relacionado['imagen']) }}" 
                             class="card-img-top" alt="{{ $relacionado['nombre'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $relacionado['nombre'] }}</h5>
                            <p class="card-text text-muted small">
                                {{ Str::limit($relacionado['descripcion'], 80) }}
                            </p>
                            <p class="text-success fw-bold">
                                S/ {{ number_format($relacionado['precio'], 2) }}
                            </p>
                            <a href="{{ route('productos.show', $relacionado['id']) }}" 
                               class="btn btn-outline-success btn-sm">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection