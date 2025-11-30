@extends('layouts.admin')

@section('title', 'Gestión de Productos')
@section('page-title', 'Gestión de Productos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Productos</h2>
    <div>
        <a href="{{ route('admin.productos.create') }}" class="btn btn-success">
            <i class="fa fa-plus"></i> Nuevo Producto
        </a>
        
    </div>
</div>

<!-- Filtros -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.productos.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos los estados</option>
                    <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                    <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="categoria" class="form-label">Categoría</label>
                <select class="form-select" id="categoria" name="categoria">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="vendedor" class="form-label">Vendedor</label>
                <select class="form-select" id="vendedor" name="vendedor">
                    <option value="">Todos los vendedores</option>
                    @foreach($vendedores as $vendedor)
                        <option value="{{ $vendedor->id }}" {{ request('vendedor') == $vendedor->id ? 'selected' : '' }}>
                            {{ $vendedor->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">Buscar</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nombre...">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-cubes"></i> Lista de Productos
        </h6>
        <span class="badge bg-light text-dark">{{ $productos->total() }} productos</span>
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
                        <th>Vendedor</th>
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
                        <td>
                            @if($producto->vendedor)
                                <small>{{ $producto->vendedor->nombre }}</small>
                            @else
                                <span class="text-muted">Admin</span>
                            @endif
                        </td>
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
                                                                                               
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $producto->id }}"
                                        title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal de Confirmación de Eliminación -->
                            <div class="modal fade" id="deleteModal{{ $producto->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar Eliminación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que quieres eliminar el producto <strong>"{{ $producto->nombre }}"</strong>?</p>
                                            <div class="alert alert-warning">
                                                <i class="fa fa-exclamation-triangle"></i>
                                                Esta acción no se puede deshacer.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Eliminar Producto</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $productos->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fa fa-cubes fa-4x text-muted mb-3"></i>
            <h4>No hay productos registrados</h4>
            <p class="text-muted">Comienza creando tu primer producto o revisa los filtros aplicados.</p>
            <a href="{{ route('admin.productos.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Crear Primer Producto
            </a>
        </div>
        @endif
    </div>
</div>
@endsection