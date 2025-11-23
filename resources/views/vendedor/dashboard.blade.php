@extends('layouts.vendedor')

@section('title', 'Dashboard Vendedor')
@section('page-title', 'Dashboard de Mi Tienda')

@section('content')
<!-- Estadísticas Principales -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Ventas Totales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['total_ventas'] }}
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
                            Productos Activos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['productos_activos'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-cube stat-icon text-success"></i>
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
                            Productos Totales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticas['total_productos'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-boxes stat-icon text-info"></i>
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
                <a href="{{ route('vendedor.pedidos') }}" class="btn btn-light btn-sm">Ver Todos</a>
            </div>
            <div class="card-body">
                @if($pedidosRecientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Pedido #</th>
                                    <th>Cliente</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedidosRecientes as $venta)
                                <tr>
                                    <td><strong>#{{ $venta->pedido->id }}</strong></td>
                                    <td>{{ $venta->pedido->usuario->nombre }}</td>
                                    <td>{{ $venta->producto->nombre }}</td>
                                    <td>{{ $venta->cantidad }}</td>
                                    <td>S/ {{ number_format($venta->precio_venta * $venta->cantidad, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $venta->pedido->estado == 'pendiente' ? 'warning' : ($venta->pedido->estado == 'confirmado' ? 'info' : ($venta->pedido->estado == 'entregado' ? 'success' : 'secondary')) }}">
                                            {{ ucfirst($venta->pedido->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">
                        <i class="fa fa-shopping-cart fa-2x mb-3"></i><br>
                        No hay pedidos recientes
                    </p>
                @endif
            </div>
        </div>

        <!-- Productos con Stock Bajo -->
        @if($stockBajo->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-exclamation-triangle"></i> Productos con Stock Bajo
                </h6>
                <span class="badge bg-danger">{{ $stockBajo->count() }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Stock Actual</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockBajo as $producto)
                            <tr>
                                <td>
                                    <strong>{{ $producto->nombre }}</strong><br>
                                    <small class="text-muted">{{ $producto->categoria->nombre }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $producto->stock <= 5 ? 'danger' : 'warning' }}">
                                        {{ $producto->stock }} {{ $producto->unidad }}
                                    </span>
                                </td>
                                <td>S/ {{ number_format($producto->precio, 2) }}</td>
                                <td>
                                    <a href="{{ route('vendedor.productos.editar', $producto->id) }}" 
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="fa fa-edit"></i> Actualizar
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    <a href="{{ route('vendedor.productos.crear') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> Nuevo Producto
                    </a>
                    <a href="{{ route('vendedor.productos') }}" class="btn btn-outline-success">
                        <i class="fa fa-cubes"></i> Gestionar Productos
                    </a>
                    <a href="{{ route('vendedor.pedidos') }}" class="btn btn-outline-primary">
                        <i class="fa fa-shopping-cart"></i> Ver Pedidos
                    </a>
                    <a href="{{ route('vendedor.ventas') }}" class="btn btn-outline-info">
                        <i class="fa fa-chart-line"></i> Ver Ventas
                    </a>
                </div>
            </div>
        </div>

        <!-- Productos Pendientes de Aprobación -->
        @if($productosPendientes->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-clock-o"></i> Pendientes de Aprobación
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($productosPendientes as $producto)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $producto->nombre }}</h6>
                            <small class="text-muted">{{ $producto->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 small text-muted">{{ Str::limit($producto->descripcion, 50) }}</p>
                        <small class="text-muted">S/ {{ number_format($producto->precio, 2) }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Información de la Tienda -->
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-store"></i> Mi Tienda
                </h6>
            </div>
            <div class="card-body">
                @php
                    $perfilVendedor = auth()->user()->perfilVendedor;
                @endphp
                @if($perfilVendedor)
                    <h6>{{ auth()->user()->nombre }}</h6>
                    <p class="small text-muted mb-2">{{ $perfilVendedor->descripcion }}</p>
                    
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fa fa-map-marker"></i> {{ $perfilVendedor->direccion }}
                        </small>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fa fa-truck"></i> 
                            {{ ucfirst($perfilVendedor->metodos_entrega) }}
                        </small>
                    </div>
                    
                    @if($perfilVendedor->horario_atencion)
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fa fa-clock-o"></i> {{ $perfilVendedor->horario_atencion }}
                        </small>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">Calificación:</small><br>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $perfilVendedor->calificacion_promedio)
                                    <i class="fa fa-star text-warning"></i>
                                @else
                                    <i class="fa fa-star-o text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Ventas:</small><br>
                            <strong>{{ $perfilVendedor->total_ventas }}</strong>
                        </div>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">
                        <i class="fa fa-info-circle"></i><br>
                        Completa tu perfil de vendedor
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection