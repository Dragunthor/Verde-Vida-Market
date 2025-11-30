@extends('layouts.admin')

@section('title', 'Gestión de Categorías')
@section('page-title', 'Gestión de Categorías')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Categorías</h2>
    <a href="{{ route('admin.categorias.create') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Nueva Categoría
    </a>
</div>

<div class="card shadow">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-tags"></i> Lista de Categorías
        </h6>
        <span class="badge bg-light text-dark">{{ $categorias->count() }} categorías</span>
    </div>
    <div class="card-body">
        @if($categorias->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Productos</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias->sortByDesc('productos_count') as $categoria)
                    <tr>
                        <td>
                            <img src="{{ $categoria->imagen_url ?: config('app.placeholder_image') }}" 
                                 alt="{{ $categoria->nombre }}" 
                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ $categoria->nombre }}</strong>
                        </td>
                        <td>
                            @if($categoria->descripcion)
                                {{ Str::limit($categoria->descripcion, 50) }}
                            @else
                                <span class="text-muted">Sin descripción</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $categoria->productos_count > 0 ? 'primary' : 'secondary' }}">
                                {{ $categoria->productos_count }} productos
                            </span>
                        </td>
                        <td>{{ $categoria->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.categorias.edit', $categoria->id) }}" 
                                   class="btn btn-outline-primary" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $categoria->id }}"
                                        title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal de Confirmación de Eliminación -->
                            <div class="modal fade" id="deleteModal{{ $categoria->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar Eliminación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que quieres eliminar la categoría <strong>"{{ $categoria->nombre }}"</strong>?</p>
                                            @if($categoria->productos_count > 0)
                                                <div class="alert alert-warning">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    Esta categoría tiene {{ $categoria->productos_count }} productos asociados. 
                                                    Al eliminarla, estos productos quedarán sin categoría.
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('admin.categorias.destroy', $categoria->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Eliminar Categoría</button>
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
        @else
        <div class="text-center py-5">
            <i class="fa fa-tags fa-4x text-muted mb-3"></i>
            <h4>No hay categorías registradas</h4>
            <p class="text-muted">Comienza creando tu primera categoría para organizar los productos.</p>
            <a href="{{ route('admin.categorias.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Crear Primera Categoría
            </a>
        </div>
        @endif
    </div>
</div>
@endsection