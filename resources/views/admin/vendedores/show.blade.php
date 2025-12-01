@extends('layouts.admin')

@section('title', 'Detalles del Vendedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detalles del Vendedor</h2>
    <a href="{{ route('admin.vendedores') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver a Vendedores
    </a>
</div>

<div class="row">
    <!-- Información Principal -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-user"></i> Información del Vendedor
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ $vendedor->usuario->nombre }}</h5>
                        <p class="text-muted mb-1">
                            <i class="fa fa-envelope"></i> {{ $vendedor->usuario->email }}
                        </p>
                        <p class="text-muted mb-1">
                            <i class="fa fa-phone"></i> {{ $vendedor->usuario->telefono ?? 'No especificado' }}
                        </p>
                        <p class="text-muted mb-3">
                            <i class="fa fa-map-marker"></i> {{ $vendedor->usuario->direccion ?? 'No especificada' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="text-end">
                            <span class="badge bg-{{ $vendedor->activo_vendedor ? 'success' : 'warning' }} fs-6">
                                {{ $vendedor->activo_vendedor ? 'Activo' : 'Pendiente' }}
                            </span>
                            <div class="mt-2">
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star{{ $i <= $vendedor->calificacion_promedio ? '' : '-o' }}"></i>
                                    @endfor
                                </span>
                                <small class="text-muted">({{ $vendedor->calificacion_promedio }}/5)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="border rounded p-3">
                            <h4 class="text-primary">{{ $vendedor->total_ventas }}</h4>
                            <small class="text-muted">Total Ventas</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="border rounded p-3">
                            <h4 class="text-success">{{ $vendedor->usuario->productos->count() }}</h4>
                            <small class="text-muted">Productos</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="border rounded p-3">
                            <h4 class="text-info">{{ $vendedor->resenas->count() }}</h4>
                            <small class="text-muted">Reseñas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la Tienda -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-store"></i> Información de la Tienda
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Descripción:</strong>
                    <p class="mt-1">{{ $vendedor->descripcion ?? 'Sin descripción' }}</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fa fa-map-marker"></i> Dirección de Operación:</strong>
                        <p class="mt-1">{{ $vendedor->direccion ?? 'No especificada' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fa fa-truck"></i> Métodos de Entrega:</strong>
                        <p class="mt-1">
                            @if($vendedor->metodos_entrega == 'recogida')
                                <span class="badge bg-primary">Recogida en Tienda</span>
                            @elseif($vendedor->metodos_entrega == 'delivery')
                                <span class="badge bg-success">Delivery</span>
                            @else
                                <span class="badge bg-info">Ambos</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <strong><i class="fa fa-clock-o"></i> Horario de Atención:</strong>
                    <p class="mt-1">{{ $vendedor->horario_atencion ?? 'No especificado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Acciones -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-cogs"></i> Acciones
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(!$vendedor->activo_vendedor)
                        <form method="POST" action="{{ route('admin.vendedores.aprobar', $vendedor->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fa fa-check"></i> Aprobar Vendedor
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.vendedores.desactivar', $vendedor->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning w-100 mb-2" 
                                    onclick="return confirm('¿Estás seguro de desactivar este vendedor?')">
                                <i class="fa fa-times"></i> Desactivar Vendedor
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.vendedores.edit', $vendedor->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fa fa-edit"></i> Editar Información
                    </a>

                    <a href="{{ route('admin.vendedores.productos', $vendedor->id) }}" class="btn btn-info w-100 mb-2">
                        <i class="fa fa-cubes"></i> Ver Productos
                    </a>
                </div>
            </div>
        </div>

        <!-- Información de Registro -->
        <div class="card shadow">
            <div class="card-header bg-light">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-info-circle"></i> Información de Registro
                </h6>
            </div>
            <div class="card-body">
                <p><strong>Fecha de Registro:</strong><br>
                {{ $vendedor->created_at->format('d/m/Y H:i') }}</p>

                <p><strong>Última Actualización:</strong><br>
                {{ $vendedor->updated_at->format('d/m/Y H:i') }}</p>

                <p><strong>Estado:</strong><br>
                <span class="badge bg-{{ $vendedor->activo_vendedor ? 'success' : 'warning' }}">
                    {{ $vendedor->activo_vendedor ? 'Activo' : 'Pendiente de Aprobación' }}
                </span></p>
            </div>
        </div>
    </div>
</div>
@endsection