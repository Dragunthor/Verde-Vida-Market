@extends('layouts.admin')

@section('title', 'Gestión de Productos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Productos</h2>
    <a href="{{ route('admin.productos.crear') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Nuevo Producto
    </a>
</div>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-cubes"></i> Lista de Productos
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
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
                            <img src="{{ asset('images/' . $producto['imagen']) }}" 
                                 alt="{{ $producto['nombre'] }}" 
                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ $producto['nombre'] }}</strong><br>
                            <small class="text-muted">{{ $producto['unidad'] }}</small>
                        </td>
                        <td>{{ $producto['categoria_nombre'] }}</td>
                        <td>S/ {{ number_format($producto['precio'], 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $producto['stock'] > 10 ? 'success' : ($producto['stock'] > 0 ? 'warning' : 'danger') }}">
                                {{ $producto['stock'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $producto['activo'] ? 'success' : 'secondary' }}">
                                {{ $producto['activo'] ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.productos.editar', $producto['id']) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection