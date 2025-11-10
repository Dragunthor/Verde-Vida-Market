@extends('layouts.app')

@section('title', $producto->nombre)

@section('content')
<!-- Migas de pan -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
        <li class="breadcrumb-item active">{{ $producto->nombre }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-6">
        <div class="product-image">
            <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('images/placeholder.jpg') }}" 
                 class="img-fluid rounded" alt="{{ $producto->nombre }}" style="max-height: 500px; object-fit: cover;">
        </div>
    </div>
    
    <div class="col-md-6">
        <h1 class="product-title">{{ $producto->nombre }}</h1>
        
        <!-- Información del vendedor -->
        @if($producto->vendedor && $producto->vendedor->perfilVendedor)
        <div class="vendedor-info mb-3">
            <div class="d-flex align-items-center">
                <small class="text-muted me-2">Vendido por:</small>
                <a href="{{ route('vendedores.show', $producto->vendedor->id) }}" class="text-decoration-none">
                    <strong>{{ $producto->vendedor->nombre }}</strong>
                </a>
                @if($producto->vendedor->perfilVendedor->calificacion_promedio > 0)
                <span class="ms-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $producto->vendedor->perfilVendedor->calificacion_promedio)
                            <i class="fa fa-star text-warning" style="font-size: 0.8em;"></i>
                        @else
                            <i class="fa fa-star-o text-muted" style="font-size: 0.8em;"></i>
                        @endif
                    @endfor
                </span>
                @endif
            </div>
        </div>
        @endif
        
        <div class="product-meta mb-3">
            <span class="badge bg-success">{{ $producto->categoria->nombre }}</span>
            @if($producto->origen)
                <span class="badge bg-info">{{ $producto->origen }}</span>
            @endif
            @if(!$producto->aprobado)
                <span class="badge bg-warning">Pendiente de aprobación</span>
            @endif
        </div>
        
        <div class="product-price mb-4">
            <h2 class="text-success">S/ {{ number_format($producto->precio, 2) }}</h2>
            <small class="text-muted">por {{ $producto->unidad }}</small>
        </div>
        
        <div class="product-info mb-4">
            <h5>Descripción</h5>
            <p class="text-muted">{!! nl2br(e($producto->descripcion)) !!}</p>
        </div>
        
        <div class="product-details mb-4">
            <div class="row">
                <div class="col-6">
                    <strong>Stock disponible:</strong><br>
                    <span class="{{ $producto->stock > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $producto->stock }} {{ $producto->unidad }}
                    </span>
                </div>
                <div class="col-6">
                    <strong>Origen:</strong><br>
                    {{ $producto->origen ?? 'No especificado' }}
                </div>
            </div>
        </div>
        
        @if($producto->estaDisponible())
            <form method="POST" action="{{ route('carrito.agregar') }}" class="mb-4">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label for="cantidad" class="form-label"><strong>Cantidad:</strong></label>
                    </div>
                    <div class="col-auto">
                        <input type="number" class="form-control" id="cantidad" name="cantidad" 
                               value="1" min="1" max="{{ $producto->stock }}" 
                               style="width: 100px;">
                    </div>
                    <div class="col-auto">
                        <span class="text-muted">{{ $producto->unidad }}</span>
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
                <h5><i class="fa fa-exclamation-triangle"></i> Producto No Disponible</h5>
                <p class="mb-0">
                    @if(!$producto->activo)
                        Este producto ha sido desactivado.
                    @elseif(!$producto->aprobado)
                        Este producto está pendiente de aprobación.
                    @else
                        Este producto está agotado temporalmente.
                    @endif
                </p>
            </div>
        @endif
        
        <div class="product-actions">
            <a href="{{ route('productos.index', ['categoria' => $producto->categoria_id]) }}" 
               class="btn btn-outline-success w-100">
                <i class="fa fa-arrow-left"></i> Ver más productos de {{ $producto->categoria->nombre }}
            </a>
        </div>
    </div>
</div>

<!-- Reseñas del producto -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-4">Reseñas del Producto</h3>
        
        @if($producto->resenas->where('aprobado', true)->count() > 0)
            <div class="row">
                @foreach($producto->resenas->where('aprobado', true)->take(6) as $resena)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">{{ $resena->usuario->nombre }}</h6>
                                    <small class="text-muted">{{ $resena->created_at->format('d/m/Y') }}</small>
                                </div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $resena->calificacion)
                                            <i class="fa fa-star text-warning"></i>
                                        @else
                                            <i class="fa fa-star-o text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="card-text">{{ $resena->comentario }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <i class="fa fa-comments fa-2x text-muted mb-3"></i>
                <p class="text-muted">Este producto aún no tiene reseñas.</p>
            </div>
        @endif
    </div>
</div>

<!-- Productos relacionados -->
@if($productosRelacionados->count() > 0)
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-4">Productos Relacionados</h3>
        <div class="row">
            @foreach($productosRelacionados as $relacionado)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ $relacionado->imagen ? asset('storage/' . $relacionado->imagen) : asset('images/placeholder.jpg') }}" 
                             class="card-img-top" alt="{{ $relacionado->nombre }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $relacionado->nombre }}</h5>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($relacionado->descripcion, 80) }}
                            </p>
                            <p class="text-success fw-bold">
                                S/ {{ number_format($relacionado->precio, 2) }}
                            </p>
                            <a href="{{ route('productos.show', $relacionado->id) }}" 
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