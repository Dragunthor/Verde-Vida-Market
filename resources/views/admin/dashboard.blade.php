@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard de Administración</h2>
    <span class="text-muted">Bienvenido, {{ session('usuario.nombre') }}</span>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Ventas Totales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            S/ {{ number_format($stats['ventas_totales'], 2) }}
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
                            {{ $stats['total_pedidos'] }}
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
                            Pedidos Pendientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['pedidos_pendientes'] }}
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
                            Total Clientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['total_clientes'] }}
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

<div class="row">
    <!-- Pedidos Recientes -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-history"></i> Pedidos Recientes
                </h6>
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
                                <td>#{{ $pedido['id'] }}</td>
                                <td>{{ $pedido['cliente_nombre'] }}</td>
                                <td>{{ $pedido['fecha_pedido'] }}</td>
                                <td>S/ {{ number_format($pedido['total'], 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $pedido['estado'] == 'pendiente' ? 'warning' : ($pedido['estado'] == 'confirmado' ? 'info' : 'success') }}">
                                        {{ ucfirst($pedido['estado']) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.pedidos.gestionar', $pedido['id']) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('admin.pedidos') }}" class="btn btn-success btn-sm">Ver todos los pedidos</a>
            </div>
        </div>
    </div>

    <!-- Stock Bajo -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-exclamation-triangle"></i> Stock Bajo
                </h6>
            </div>
            <div class="card-body">
                @if(count($stockBajo) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($stockBajo as $producto)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $producto['nombre'] }}</h6>
                                <small class="text-muted">Stock: {{ $producto['stock'] }}</small>
                            </div>
                            <a href="{{ route('admin.productos.editar', $producto['id']) }}" 
                               class="btn btn-sm btn-outline-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">Todo el stock está en niveles normales</p>
                @endif
                <a href="{{ route('admin.productos') }}" class="btn btn-warning btn-sm mt-3 w-100">Gestionar Productos</a>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card shadow mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-bolt"></i> Acciones Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.productos.crear') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> Nuevo Producto
                    </a>
                    <a href="{{ route('admin.categorias.crear') }}" class="btn btn-outline-success">
                        <i class="fa fa-tag"></i> Nueva Categoría
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection