@extends('layouts.admin')

@section('title', 'Gestión de Vendedores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Vendedores</h2>
</div>

<!-- Vendedores Pendientes -->
@if($vendedoresPendientes->count() > 0)
<div class="card shadow mb-4">
    <div class="card-header bg-warning text-dark">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-clock-o"></i> Vendedores Pendientes de Aprobación
            <span class="badge bg-danger ms-2">{{ $vendedoresPendientes->count() }}</span>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha Solicitud</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendedoresPendientes as $vendedor)
                    <tr>
                        <td>
                            <strong>{{ $vendedor->usuario->nombre }}</strong>
                        </td>
                        <td>{{ $vendedor->usuario->email }}</td>
                        <td>{{ $vendedor->created_at->format('d/m/Y') }}</td>
                        <td>{{ Str::limit($vendedor->descripcion, 50) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.vendedores.show', $vendedor->id) }}" 
                                   class="btn btn-outline-primary" title="Ver detalle">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.vendedores.aprobar', $vendedor->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success" title="Aprobar">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Vendedores Activos -->
<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-check-circle"></i> Vendedores Activos
            <span class="badge bg-light text-dark ms-2">{{ $vendedoresActivos->count() }}</span>
        </h6>
    </div>
    <div class="card-body">
        @if($vendedoresActivos->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Calificación</th>
                        <th>Total Ventas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendedoresActivos as $vendedor)
                    <tr>
                        <td>
                            <strong>{{ $vendedor->usuario->nombre }}</strong>
                        </td>
                        <td>{{ $vendedor->usuario->email }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star{{ $i <= $vendedor->calificacion_promedio ? '' : '-o' }}"></i>
                                    @endfor
                                </span>
                                <small class="text-muted ms-1">({{ $vendedor->calificacion_promedio }})</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $vendedor->total_ventas }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">Activo</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.vendedores.show', $vendedor->id) }}" 
                                   class="btn btn-outline-primary" title="Ver detalle">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.vendedores.edit', $vendedor->id) }}" 
                                   class="btn btn-outline-warning" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.vendedores.productos', $vendedor->id) }}" 
                                   class="btn btn-outline-info" title="Ver productos">
                                    <i class="fa fa-cubes"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fa fa-users fa-3x text-muted mb-3"></i>
            <p class="text-muted">No hay vendedores activos.</p>
        </div>
        @endif
    </div>
</div>
@endsection