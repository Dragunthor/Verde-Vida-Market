@extends('layouts.app')

@section('title', $vendedor->nombre . ' - VerdeVida Market')

@section('content')
<!-- Migas de pan -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('vendedores.index') }}">Vendedores</a></li>
        <li class="breadcrumb-item active">{{ $vendedor->nombre }}</li>
    </ol>
</nav>

<div class="row">
    <!-- Información del vendedor -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fa fa-store fa-3x text-success"></i>
                </div>
                <h3>{{ $vendedor->nombre }}</h3>
                
                @if($vendedor->perfilVendedor && $vendedor->perfilVendedor->calificacion_promedio > 0)
                    <div class="mb-3">
                        <div class="text-warning mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $vendedor->perfilVendedor->calificacion_promedio)
                                    <i class="fa fa-star"></i>
                                @else
                                    <i class="fa fa-star-o"></i>
                                @endif
                            @endfor
                            <span class="text-muted ms-1">({{ $vendedor->perfilVendedor->calificacion_promedio }})</span>
                        </div>
                        <small class="text-muted">{{ $vendedor->perfilVendedor->total_ventas ?? 0 }} ventas</small>
                    </div>
                @else
                    <div class="mb-3">
                        <small class="text-muted">Nuevo vendedor</small>
                    </div>
                @endif

                <div class="vendedor-stats mb-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fw-bold h5">{{ $productos->count() }}</div>
                            <small class="text-muted">Productos</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold h5">{{ $vendedor->perfilVendedor->total_ventas ?? 0 }}</div>
                            <small class="text-muted">Ventas</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold h5">{{ $vendedor->perfilVendedor->calificacion_promedio ?? 'N/A' }}</div>
                            <small class="text-muted">Calificación</small>
                        </div>
                    </div>
                </div>

                @if($vendedor->perfilVendedor)
                    <div class="vendedor-info text-start">
                        <div class="mb-3">
                            <h6><i class="fa fa-info-circle"></i> Información</h6>
                            <p class="text-muted small">
                                {{ $vendedor->perfilVendedor->descripcion ?? 'Vendedor de productos orgánicos y locales.' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6><i class="fa fa-truck"></i> Métodos de Entrega</h6>
                            <p class="text-muted small">
                                {{ ucfirst($vendedor->perfilVendedor->metodos_entrega) }}
                            </p>
                        </div>

                        @if($vendedor->perfilVendedor->horario_atencion)
                            <div class="mb-3">
                                <h6><i class="fa fa-clock-o"></i> Horario de Atención</h6>
                                <p class="text-muted small">
                                    {{ $vendedor->perfilVendedor->horario_atencion }}
                                </p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <h6><i class="fa fa-map-marker"></i> Ubicación</h6>
                            <p class="text-muted small">
                                {{ $vendedor->perfilVendedor->direccion ?? 'Ubicación no especificada' }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Productos del vendedor -->
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Productos de {{ $vendedor->nombre }}</h4>
            <span class="text-muted">{{ $productos->count() }} productos</span>
        </div>

        @if($productos->count() > 0)
            <div class="row">
                @foreach($productos as $producto)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <img src="{{ $producto->imagen_url ?: config('app.placeholder_image') }}" 
                                 class="card-img-top" alt="{{ $producto->nombre }}" style="height: 200px; object-fit: cover;">
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $producto->nombre }}</h5>
                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($producto->descripcion, 80) }}
                                </p>
                                
                                <div class="mt-auto">
                                    <p class="text-success fw-bold h5 mb-2">
                                        S/ {{ number_format($producto->precio, 2) }}
                                        <small class="text-muted">/{{ $producto->unidad }}</small>
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('productos.show', $producto->id) }}" 
                                           class="btn btn-outline-success btn-sm">
                                            <i class="fa fa-eye"></i> Ver
                                        </a>
                                        
                                        @if($producto->estaDisponible())
                                            <form method="POST" action="{{ route('carrito.agregar') }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                                <input type="hidden" name="cantidad" value="1">
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        title="Agregar al carrito">
                                                    <i class="fa fa-cart-plus"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                Sin Stock
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                <h5>Este vendedor aún no tiene productos</h5>
                <p class="text-muted">Vuelve pronto para ver sus productos.</p>
            </div>
        @endif
    </div>
</div>

<!-- Reseñas del vendedor -->
@if($vendedor->perfilVendedor && $vendedor->perfilVendedor->resenas->count() > 0)
<div class="row mt-5">
    <div class="col-12">
        <h4 class="mb-4">Reseñas de Clientes</h4>
        <div class="row">
            @foreach($vendedor->perfilVendedor->resenas->take(6) as $resena)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">{{ $resena->usuario->nombre ?? 'Cliente' }}</h6>
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
    </div>
</div>
@endif
@endsection