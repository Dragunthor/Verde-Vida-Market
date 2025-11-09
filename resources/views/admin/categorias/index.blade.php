@extends('layouts.admin')

@section('title', 'Gestión de Categorías')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Categorías</h2>
    <a href="{{ route('admin.categorias.crear') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Nueva Categoría
    </a>
</div>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-tags"></i> Lista de Categorías
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $categoria)
                    <tr>
                        <td>
                            <img src="{{ asset('images/' . $categoria['imagen']) }}" 
                                 alt="{{ $categoria['nombre'] }}" 
                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ $categoria['nombre'] }}</strong>
                        </td>
                        <td>{{ $categoria['descripcion'] }}</td>
                        <td>{{ date('d/m/Y', strtotime($categoria['created_at'])) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.categorias.editar', $categoria['id']) }}" 
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