@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard de Administración')

@section('content')
<!-- Estadísticas Principales -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Ingresos Totales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            S/ {{ number_format($estadisticas['ingresos_totales'], 2) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-money stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Pedidos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['total_pedidos'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-shopping-cart stat-icon text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pendientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['pedidos_pendientes'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-clock-o stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Usuarios</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['total_usuarios'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-users stat-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Segunda Fila de Estadísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Vendedores Pendientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['vendedores_pendientes'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-user-plus stat-icon text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Productos Pendientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['productos_pendientes'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-cubes stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Reportes Activos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['reportes_pendientes'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-flag stat-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Reseñas Pendientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['resenas_pendientes'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-star-half-o stat-icon text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Columna Izquierda -->
    <div class="col-lg-8">
        <!-- Pedidos Recientes -->
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-history"></i> Pedidos Recientes
                </h6>
                <a href="{{ route('admin.pedidos') }}" class="btn btn-light btn-sm">Ver Todos</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Pedido #</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidosRecientes as $pedido)
                            <tr>
                                <td><strong>#{{ $pedido->id }}</strong></td>
                                <td>{{ $pedido->usuario->nombre }}</td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td>S/ {{ number_format($pedido->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $pedido->estado == 'pendiente' ? 'warning' : ($pedido->estado == 'confirmado' ? 'info' : ($pedido->estado == 'entregado' ? 'success' : 'secondary')) }}">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.pedidos.show', $pedido->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Vendedores Pendientes -->
        @if($vendedoresPendientes->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-user-plus"></i> Vendedores Pendientes de Aprobación
                </h6>
                <span class="badge bg-danger">{{ $vendedoresPendientes->count() }}</span>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($vendedoresPendientes as $vendedor)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $vendedor->usuario->nombre }}</h6>
                            <p class="mb-1 text-muted small">{{ $vendedor->usuario->email }}</p>
                            <small class="text-muted">Solicitado: {{ $vendedor->created_at->format('d/m/Y') }}</small>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('admin.vendedores.show', $vendedor->usuario_id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.vendedores.aprobar', $vendedor->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="fa fa-check"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Columna Derecha -->
    <div class="col-lg-4">
        <!-- Acciones Rápidas -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-bolt"></i> Acciones Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.productos.create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> Nuevo Producto
                    </a>
                    <a href="{{ route('admin.categorias.create') }}" class="btn btn-outline-success">
                        <i class="fa fa-tag"></i> Nueva Categoría
                    </a>
                    <a href="{{ route('admin.vendedores') }}" class="btn btn-warning">
                        <i class="fa fa-users"></i> Gestionar Vendedores
                    </a>
                    <a href="{{ route('admin.reportes') }}" class="btn btn-danger">
                        <i class="fa fa-flag"></i> Ver Reportes
                    </a>
                </div>
            </div>
        </div>

        <!-- Stock Bajo -->
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-exclamation-triangle"></i> Stock Bajo
                </h6>
            </div>
            <div class="card-body">
                @if($stockBajo->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($stockBajo as $producto)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $producto->nombre }}</h6>
                                <small class="text-muted">
                                    Stock: {{ $producto->stock }} {{ $producto->unidad }}
                                    @if($producto->vendedor)
                                        <br>Vendedor: {{ $producto->vendedor->nombre }}
                                    @endif
                                </small>
                            </div>
                            <a href="{{ route('admin.productos.edit', $producto->id) }}" 
                               class="btn btn-sm btn-outline-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">
                        <i class="fa fa-check-circle text-success"></i><br>
                        Todo el stock está en niveles normales
                    </p>
                @endif
            </div>
        </div>

        <!-- Reportes Recientes -->
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-flag"></i> Reportes Recientes
                </h6>
            </div>
            <div class="card-body">
                @if($reportesRecientes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($reportesRecientes as $reporte)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $reporte->titulo }}</h6>
                                <small class="text-muted">{{ $reporte->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 small text-muted">{{ Str::limit($reporte->descripcion, 50) }}</p>
                            <small>
                                <span class="badge bg-{{ $reporte->estado == 'pendiente' ? 'warning' : 'info' }}">
                                    {{ $reporte->estado }}
                                </span>
                                • {{ $reporte->usuario->nombre }}
                            </small>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('admin.reportes') }}" class="btn btn-outline-danger btn-sm w-100 mt-3">
                        Ver todos los reportes
                    </a>
                @else
                    <p class="text-muted text-center mb-0">
                        <i class="fa fa-check-circle text-success"></i><br>
                        No hay reportes pendientes
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection