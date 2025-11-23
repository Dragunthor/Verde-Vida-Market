@extends('layouts.app')

@section('title', 'Nuestros Vendedores - VerdeVida Market')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Nuestros Vendedores</h1>
            <span class="text-muted">{{ $vendedores->total() }} vendedores encontrados</span>
        </div>

        @if($vendedores->count() > 0)
            <div class="row">
                @foreach($vendedores as $vendedor)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1">{{ $vendedor->nombre }}</h5>
                                        @if($vendedor->perfilVendedor)
                                            <p class="text-muted small mb-2">
                                                <i class="fa fa-map-marker"></i> 
                                                {{ $vendedor->perfilVendedor->direccion ?? 'Ubicación no especificada' }}
                                            </p>
                                        @endif
                                    </div>
                                    @if($vendedor->perfilVendedor && $vendedor->perfilVendedor->calificacion_promedio > 0)
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $vendedor->perfilVendedor->calificacion_promedio)
                                                    <i class="fa fa-star"></i>
                                                @else
                                                    <i class="fa fa-star-o"></i>
                                                @endif
                                            @endfor
                                            <small class="text-muted">({{ $vendedor->perfilVendedor->total_ventas ?? 0 }})</small>
                                        </div>
                                    @endif
                                </div>

                                @if($vendedor->perfilVendedor && $vendedor->perfilVendedor->descripcion)
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($vendedor->perfilVendedor->descripcion, 120) }}
                                    </p>
                                @else
                                    <p class="card-text text-muted small">
                                        Vendedor de productos orgánicos y locales.
                                    </p>
                                @endif

                                <div class="vendedor-stats mb-3">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <small class="text-muted">Productos</small>
                                            <div class="fw-bold">{{ $vendedor->productos->count() }}</div>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted">Ventas</small>
                                            <div class="fw-bold">{{ $vendedor->perfilVendedor->total_ventas ?? 0 }}</div>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted">Calificación</small>
                                            <div class="fw-bold">
                                                {{ $vendedor->perfilVendedor->calificacion_promedio ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vendedor-meta">
                                    @if($vendedor->perfilVendedor)
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fa fa-truck"></i> 
                                                {{ ucfirst($vendedor->perfilVendedor->metodos_entrega) }}
                                            </small>
                                        </div>
                                        @if($vendedor->perfilVendedor->horario_atencion)
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fa fa-clock-o"></i> 
                                                    {{ $vendedor->perfilVendedor->horario_atencion }}
                                                </small>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <a href="{{ route('vendedores.show', $vendedor->id) }}" 
                                   class="btn btn-outline-success btn-sm w-100">
                                    <i class="fa fa-store"></i> Ver Tienda
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $vendedores->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-store fa-3x text-muted mb-3"></i>
                <h4>No se encontraron vendedores</h4>
                <p class="text-muted">Próximamente tendremos más vendedores en nuestra plataforma.</p>
            </div>
        @endif
    </div>
</div>
@endsection