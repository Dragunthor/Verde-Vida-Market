@extends('layouts.app')

@section('title', 'Mis Reseñas - VerdeVida Market')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="fa fa-comments"></i> Mis Reseñas
                </h4>
            </div>
            <div class="card-body">
                <!-- Pestañas -->
                <ul class="nav nav-tabs mb-4" id="resenasTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="productos-tab" data-bs-toggle="tab" data-bs-target="#productos" type="button" role="tab">
                            <i class="fa fa-cube"></i> Reseñas de Productos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="vendedores-tab" data-bs-toggle="tab" data-bs-target="#vendedores" type="button" role="tab">
                            <i class="fa fa-store"></i> Reseñas de Vendedores
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="resenasTabsContent">
                    <!-- Reseñas de Productos -->
                    <div class="tab-pane fade show active" id="productos" role="tabpanel">
                        @if($resenasProductos->count() > 0)
                            @foreach($resenasProductos as $resena)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5 class="card-title">
                                                    <a href="{{ route('productos.show', $resena->producto->id) }}" class="text-decoration-none">
                                                        {{ $resena->producto->nombre }}
                                                    </a>
                                                </h5>
                                                <div class="text-warning mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $resena->calificacion)
                                                            <i class="fa fa-star"></i>
                                                        @else
                                                            <i class="fa fa-star-o"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <p class="card-text">{{ $resena->comentario }}</p>
                                                <small class="text-muted">
                                                    Enviada el {{ $resena->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <span class="badge bg-{{ $resena->aprobado ? 'success' : 'warning' }}">
                                                    {{ $resena->aprobado ? 'Aprobada' : 'Pendiente' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fa fa-comments fa-3x text-muted mb-3"></i>
                                <h5>No tienes reseñas de productos</h5>
                                <p class="text-muted">Tus reseñas de productos aparecerán aquí.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Reseñas de Vendedores -->
                    <div class="tab-pane fade" id="vendedores" role="tabpanel">
                        @if($resenasVendedores->count() > 0)
                            @foreach($resenasVendedores as $resena)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5 class="card-title">
                                                    <a href="{{ route('vendedores.show', $resena->vendedor->id) }}" class="text-decoration-none">
                                                        {{ $resena->vendedor->nombre }}
                                                    </a>
                                                </h5>
                                                <div class="text-warning mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $resena->calificacion)
                                                            <i class="fa fa-star"></i>
                                                        @else
                                                            <i class="fa fa-star-o"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <p class="card-text">{{ $resena->comentario }}</p>
                                                <small class="text-muted">
                                                    Enviada el {{ $resena->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <span class="badge bg-{{ $resena->aprobado ? 'success' : 'warning' }}">
                                                    {{ $resena->aprobado ? 'Aprobada' : 'Pendiente' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fa fa-store fa-3x text-muted mb-3"></i>
                                <h5>No tienes reseñas de vendedores</h5>
                                <p class="text-muted">Tus reseñas de vendedores aparecerán aquí.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection