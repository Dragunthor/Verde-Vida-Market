@extends('layouts.vendedor')

@section('title', 'Mis Productos')
@section('page-title', 'Gestión de Mis Productos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Mis Productos</h2>
    <a href="{{ route('vendedor.productos.crear') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Nuevo Producto
    </a>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $productos->count() }}</h4>
                        <p class="mb-0">Total</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-cubes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $productos->where('activo', true)->where('aprobado', true)->count() }}</h4>
                        <p class="mb-0">Activos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $productos->where('aprobado', false)->count() }}</h4>
                        <p class="mb-0">Pendientes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-clock-o fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $productos->where('stock', '<=', 5)->count() }}</h4>
                        <p class="mb-0">Stock Bajo</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-cubes"></i> Lista de Mis Productos
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
                            <small class="text-muted">{{ $producto->unidad }} • {{ $producto->origen }}</small>
                        </td>
                        <td>{{ $producto->categoria->nombre }}</td>
                        <td>S/ {{ number_format($producto->precio, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $producto->stock > 10 ? 'success' : ($producto->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $producto->stock }} {{ $producto->unidad }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @if($producto->aprobado)
                                    <span class="badge bg-success">
                                        <i class="fa fa-check"></i> Aprobado
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fa fa-clock-o"></i> Pendiente
                                    </span>
                                @endif
                                
                                @if($producto->activo)
                                    <span class="badge bg-primary">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                {{-- Botón Editar --}}
                                <a href="{{ route('vendedor.productos.editar', $producto->id) }}" 
                                class="btn btn-outline-primary" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                                
                                {{-- Botón Activar/Desactivar --}}
                                @if($producto->activo)
                                    <form method="POST" action="{{ route('vendedor.productos.toggle-activo', $producto->id) }}" 
                                        class="d-inline" onsubmit="return confirm('¿Estás seguro de desactivar este producto?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-warning" title="Desactivar">
                                            <i class="fa fa-pause"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('vendedor.productos.toggle-activo', $producto->id) }}" 
                                        class="d-inline" onsubmit="return confirm('¿Estás seguro de activar este producto?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-success" title="Activar">
                                            <i class="fa fa-play"></i>
                                        </button>
                                    </form>
                                @endif
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
            <h4>No tienes productos registrados</h4>
            <p class="text-muted">Comienza creando tu primer producto para vender en nuestra plataforma.</p>
            <a href="{{ route('vendedor.productos.crear') }}" class="btn btn-success btn-lg">
                <i class="fa fa-plus"></i> Crear Mi Primer Producto
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Información Importante -->
<div class="card shadow mt-4">
    <div class="card-header bg-info text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-info-circle"></i> Información Importante
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Proceso de Aprobación</h6>
                <ul class="list-unstyled small">
                    <li><i class="fa fa-clock-o text-warning me-2"></i> Los productos nuevos requieren aprobación del administrador</li>
                    <li><i class="fa fa-check text-success me-2"></i> Una vez aprobados, aparecerán en la tienda</li>
                    <li><i class="fa fa-edit text-primary me-2"></i> Las ediciones también requieren nueva aprobación</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Consejos para Vendedores</h6>
                <ul class="list-unstyled small">
                    <li><i class="fa fa-image text-info me-2"></i> Usa imágenes de buena calidad</li>
                    <li><i class="fa fa-tag text-success me-2"></i> Mantén precios competitivos</li>
                    <li><i class="fa fa-cubes text-warning me-2"></i> Actualiza el stock regularmente</li>
                    <li><i class="fa fa-star text-warning me-2"></i> Descripciones claras aumentan las ventas</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection