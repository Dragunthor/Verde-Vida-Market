@extends('layouts.admin')

@section('title', 'Productos del Vendedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Productos de: {{ $vendedor->usuario->nombre }}</h2>
    <div>
        <a href="{{ route('admin.vendedores.show', $vendedor->id) }}" class="btn btn-outline-secondary me-2">
            <i class="fa fa-arrow-left"></i> Volver al Vendedor
        </a>
        <a href="{{ route('admin.vendedores') }}" class="btn btn-outline-secondary">
            <i class="fa fa-users"></i> Todos los Vendedores
        </a>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-cubes"></i> Productos del Vendedor
        </h6>
        <span class="badge bg-light text-dark">{{ $productos->count() }} productos</span>
    </div>
    <div class="card-body">
        @if($productos->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr>
                        <td>
                            <img src="{{ $producto->imagen_url ?: config('app.placeholder_image') }}" 
                                 alt="{{ $producto->nombre }}" 
                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ $producto->nombre }}</strong><br>
                            <small class="text-muted">{{ $producto->unidad }}</small>
                        </td>
                        <td>{{ $producto->categoria->nombre }}</td>
                        <td>S/ {{ number_format($producto->precio, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $producto->stock > 10 ? 'success' : ($producto->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $producto->stock }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="badge bg-{{ $producto->activo ? 'success' : 'secondary' }}">
                                    {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                                <span class="badge bg-{{ $producto->aprobado ? 'primary' : 'warning' }}">
                                    {{ $producto->aprobado ? 'Aprobado' : 'Pendiente' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.productos.edit', $producto->id) }}" 
                                   class="btn btn-outline-primary" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ route('productos.show', $producto->id) }}" 
                                   target="_blank" class="btn btn-outline-info" title="Ver en tienda">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fa fa-cubes fa-4x text-muted mb-3"></i>
            <h4>No hay productos registrados</h4>
            <p class="text-muted">Este vendedor no tiene productos en la plataforma.</p>
        </div>
        @endif
    </div>
</div>

<!-- Estadísticas -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $productos->where('activo', true)->where('aprobado', true)->count() }}</h4>
                        <p>Productos Activos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $productos->where('aprobado', false)->count() }}</h4>
                        <p>Pendientes de Aprobación</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-clock-o fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $productos->where('stock', '>', 0)->count() }}</h4>
                        <p>Con Stock Disponible</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-cubes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $productos->where('stock', 0)->count() }}</h4>
                        <p>Sin Stock</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection