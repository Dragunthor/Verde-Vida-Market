@extends('layouts.admin')

@section('title', 'Ventas y Comisiones')
@section('page-title', 'Reporte de Ventas y Comisiones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Ventas y Comisiones</h2>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-success" onclick="window.print()">
            <i class="fa fa-print"></i> Imprimir Reporte
        </button>
    </div>
</div>

<!-- Pestañas de Navegación -->
<ul class="nav nav-tabs mb-4" id="ventasTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="plataforma-tab" data-bs-toggle="tab" data-bs-target="#plataforma" type="button" role="tab">
            <i class="fa fa-globe"></i> Vista Plataforma
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
            <i class="fa fa-user"></i> Mis Ventas
        </button>
    </li>
</ul>

<div class="tab-content" id="ventasTabsContent">
    <!-- Pestaña 1: Vista Plataforma -->
    <div class="tab-pane fade show active" id="plataforma" role="tabpanel">
        <!-- Estadísticas Principales - Plataforma -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Ingresos Vendedores</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    S/ {{ number_format($estadisticas['ingresos_totales'], 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-money fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Ventas Totales</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $estadisticas['total_ventas'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Comisiones Plataforma</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    S/ {{ number_format($estadisticas['comisiones_totales'], 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-percent fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Tasa de Conversión</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($estadisticas['ventas_pagadas'] / max($estadisticas['total_ventas'], 1) * 100, 1) }}%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-line-chart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna Izquierda - Lista de Ventas -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-list"></i> Todas las Ventas de la Plataforma
                        </h6>
                        <span class="badge bg-light text-dark">{{ $ventas->count() }} ventas</span>
                    </div>
                    <div class="card-body">
                        @if($ventas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Pedido #</th>
                                        <th>Vendedor</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Venta</th>
                                        <th>Subtotal</th>
                                        <th>Comisión</th>
                                        <th>Total Vendedor</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ventas as $venta)
                                    <tr>
                                        <td>{{ $venta->created_at->format('d/m/Y') }}</td>
                                        <td>#{{ $venta->pedido->id }}</td>
                                        <td>
                                            <strong>{{ $venta->vendedor->nombre }}</strong>
                                            @if($venta->vendedor_id == auth()->id())
                                                <span class="badge bg-primary ms-1">Yo</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ $venta->vendedor->email }}</small>
                                        </td>
                                        <td>{{ $venta->producto->nombre }}</td>
                                        <td>{{ $venta->cantidad }}</td>
                                        <td>S/ {{ number_format($venta->precio_venta, 2) }}</td>
                                        <td>S/ {{ number_format($venta->precio_venta * $venta->cantidad, 2) }}</td>
                                        <td>
                                            <span class="text-danger">{{ $venta->comision_porcentaje }}%</span><br>
                                            <small>S/ {{ number_format(($venta->precio_venta * $venta->cantidad * $venta->comision_porcentaje) / 100, 2) }}</small>
                                        </td>
                                        <td>
                                            <strong>S/ {{ number_format($venta->total_vendedor, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $venta->estado_pago == 'pagado' ? 'success' : 'warning' }}">
                                                {{ ucfirst($venta->estado_pago) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fa fa-chart-line fa-4x text-muted mb-3"></i>
                            <h4>No hay ventas registradas</h4>
                            <p class="text-muted">Las ventas aparecerán aquí cuando los clientes realicen pedidos.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Derecha - Resumen -->
            <div class="col-lg-4">
                <!-- Ventas por Vendedor -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-users"></i> Ventas por Vendedor
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($ventasPorVendedor->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($ventasPorVendedor as $vendedor)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            {{ $vendedor->nombre }}
                                            @if($vendedor->id == auth()->id())
                                                <span class="badge bg-primary ms-1">Yo</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">
                                            {{ $vendedor->total_ventas }} ventas 
                                            ({{ $vendedor->ventas_pagadas }} pagadas)
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <strong>S/ {{ number_format($vendedor->total_ingresos, 2) }}</strong><br>
                                        <small class="text-muted">Com: S/ {{ number_format($vendedor->comisiones, 2) }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">No hay datos de vendedores</p>
                        @endif
                    </div>
                </div>

                <!-- Comisiones por Mes (de otros vendedores) -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-calendar"></i> Comisiones por Mes
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($comisionesPorMes->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($comisionesPorMes as $mes)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $mes->mes }}/{{ $mes->anio }}</span>
                                    <div class="text-end">
                                        <strong>S/ {{ number_format($mes->total_comisiones, 2) }}</strong><br>
                                        <small class="text-muted">
                                            {{ $mes->total_ventas }} ventas
                                        </small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">No hay comisiones este mes</p>
                        @endif
                    </div>
                </div>

                <!-- Resumen Financiero Plataforma -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-calculator"></i> Resumen Plataforma
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Ingresos Totales</h6>
                            <p class="mb-1">
                                <strong>Vendedores:</strong> 
                                S/ {{ number_format($estadisticas['ingresos_totales'], 2) }}
                            </p>
                            <p class="mb-1">
                                <strong>Plataforma:</strong> 
                                S/ {{ number_format($estadisticas['comisiones_totales'], 2) }}
                            </p>
                            <p class="mb-0">
                                <strong>Volumen Total:</strong> 
                                S/ {{ number_format($ventas->sum(function($v) { return $v->precio_venta * $v->cantidad; }), 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestaña 2: Mis Ventas -->
    <div class="tab-pane fade" id="personal" role="tabpanel">
        <!-- Estadísticas Personales -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Mis Ingresos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    S/ {{ number_format($misVentasEstadisticas['ingresos_totales'], 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-money fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Mis Ventas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $misVentasEstadisticas['total_ventas'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Tasa Personal</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($misVentasEstadisticas['ventas_pagadas'] / max($misVentasEstadisticas['total_ventas'], 1) * 100, 1) }}%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-line-chart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna Izquierda - Mis Ventas -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-user"></i> Mis Ventas Personales
                        </h6>
                        <span class="badge bg-light text-dark">{{ $misVentas->count() }} ventas</span>
                    </div>
                    <div class="card-body">
                        @if($misVentas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Pedido #</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Mi Ganancia</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($misVentas as $venta)
                                    <tr>
                                        <td>{{ $venta->created_at->format('d/m/Y') }}</td>
                                        <td>#{{ $venta->pedido->id }}</td>
                                        <td>{{ $venta->producto->nombre }}</td>
                                        <td>{{ $venta->cantidad }}</td>
                                        <td>S/ {{ number_format($venta->precio_venta, 2) }}</td>
                                        <td>
                                            <strong>S/ {{ number_format($venta->total_vendedor, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $venta->estado_pago == 'pagado' ? 'success' : 'warning' }}">
                                                {{ ucfirst($venta->estado_pago) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Total Ganado:</strong></td>
                                        <td><strong>S/ {{ number_format($misVentas->sum('total_vendedor'), 2) }}</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fa fa-user fa-4x text-muted mb-3"></i>
                            <h4>No tienes ventas personales</h4>
                            <p class="text-muted">Tus ventas personales aparecerán aquí cuando vendas productos.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Derecha - Mi Resumen -->
            <div class="col-lg-4">
                <!-- Mis Ventas por Producto -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-cube"></i> Mis Ventas por Producto
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($misVentasPorProducto->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($misVentasPorProducto as $producto)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $producto->nombre }}</h6>
                                        <small class="text-muted">
                                            {{ $producto->total_ventas }} ventas 
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <strong>S/ {{ number_format($producto->total_ingresos, 2) }}</strong><br>
                                        <small class="text-muted">{{ $producto->cantidad_total }} {{ $producto->unidad }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">No hay datos de productos</p>
                        @endif
                    </div>
                </div>

                <!-- Mi Resumen Financiero -->
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-calculator"></i> Mi Resumen
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Ingresos Personales</h6>
                            <p class="mb-1">
                                <strong>Total Ganado:</strong> 
                                S/ {{ number_format($misVentasEstadisticas['ingresos_totales'], 2) }}
                            </p>
                            <p class="mb-0">
                                <strong>Volumen Personal:</strong> 
                                S/ {{ number_format($misVentas->sum(function($v) { return $v->precio_venta * $v->cantidad; }), 2) }}
                            </p>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <h6>Métricas Personales</h6>
                            <p class="mb-1">
                                <strong>Ventas Pagadas:</strong> 
                                {{ $misVentasEstadisticas['ventas_pagadas'] }} de {{ $misVentasEstadisticas['total_ventas'] }}
                            </p>
                            <p class="mb-0">
                                <strong>Ticket Promedio:</strong> 
                                S/ {{ number_format($misVentas->avg('precio_venta'), 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection