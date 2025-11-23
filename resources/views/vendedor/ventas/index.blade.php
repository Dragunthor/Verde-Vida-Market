@extends('layouts.vendedor')

@section('title', 'Mis Ventas')
@section('page-title', 'Reporte de Mis Ventas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Reporte de Ventas</h2>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-success" onclick="window.print()">
            <i class="fa fa-print"></i> Imprimir Reporte
        </button>
    </div>
</div>

<!-- Estadísticas Principales -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Ingresos Totales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            S/ {{ number_format($estadisticasVentas['ingresos_totales'], 2) }}
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
                            {{ $estadisticasVentas['total_ventas'] }}
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
                            Ventas Pagadas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $estadisticasVentas['ventas_pagadas'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-check-circle fa-2x text-gray-300"></i>
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
                            Comisiones Pagadas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            S/ {{ number_format($estadisticasVentas['comisiones_totales'], 2) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-percent fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card shadow mb-4">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-filter"></i> Filtros de Reporte
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('vendedor.ventas') }}" class="row g-3">
            <div class="col-md-3">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                       value="{{ request('fecha_inicio') }}">
            </div>
            <div class="col-md-3">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                       value="{{ request('fecha_fin') }}">
            </div>
            <div class="col-md-3">
                <label for="estado_pago" class="form-label">Estado Pago</label>
                <select class="form-select" id="estado_pago" name="estado_pago">
                    <option value="">Todos los estados</option>
                    <option value="pagado" {{ request('estado_pago') == 'pagado' ? 'selected' : '' }}>Pagados</option>
                    <option value="pendiente" {{ request('estado_pago') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="producto" class="form-label">Producto</label>
                <select class="form-select" id="producto" name="producto">
                    <option value="">Todos los productos</option>
                    @foreach($productosVendidos as $producto)
                        <option value="{{ $producto->id }}" {{ request('producto') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-search"></i> Aplicar Filtros
                </button>
                <a href="{{ route('vendedor.ventas') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-refresh"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <!-- Columna Izquierda - Lista de Ventas -->
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-list"></i> Detalle de Ventas
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
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Venta</th>
                                <th>Subtotal</th>
                                <th>Comisión</th>
                                <th>Total</th>
                                <th>Estado Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventas as $venta)
                            <tr>
                                <td>{{ $venta->created_at->format('d/m/Y') }}</td>
                                <td>#{{ $venta->pedido->id }}</td>
                                <td>
                                    <strong>{{ $venta->producto->nombre }}</strong><br>
                                    <small class="text-muted">{{ $venta->producto->unidad }}</small>
                                </td>
                                <td>{{ $venta->cantidad }}</td>
                                <td>S/ {{ number_format($venta->precio_venta, 2) }}</td>
                                <td>S/ {{ number_format($venta->precio_venta * $venta->cantidad, 2) }}</td>
                                <td>
                                    <span class="text-danger">-{{ $venta->comision_porcentaje }}%</span><br>
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
                        <tfoot class="table-success">
                            <tr>
                                <td colspan="5" class="text-end"><strong>Totales:</strong></td>
                                <td><strong>S/ {{ number_format($ventas->sum(function($v) { return $v->precio_venta * $v->cantidad; }), 2) }}</strong></td>
                                <td><strong>S/ {{ number_format($ventas->sum(function($v) { return ($v->precio_venta * $v->cantidad * $v->comision_porcentaje) / 100; }), 2) }}</strong></td>
                                <td><strong>S/ {{ number_format($ventas->sum('total_vendedor'), 2) }}</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa fa-chart-line fa-4x text-muted mb-3"></i>
                    <h4>No hay ventas registradas</h4>
                    <p class="text-muted">Tus ventas aparecerán aquí cuando los clientes compren tus productos.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Columna Derecha - Resumen y Análisis -->
    <div class="col-lg-4">
        <!-- Resumen por Producto -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-cube"></i> Ventas por Producto
                </h6>
            </div>
            <div class="card-body">
                @if($ventasPorProducto->count() > 0)
                    <div class="list-group list-group-flush">
                    @foreach($ventasPorProducto as $producto)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $producto->nombre }}</h6>
                            <small class="text-muted">
                                {{ $producto->total_ventas }} ventas 
                                ({{ $producto->ventas_pagadas }} pagadas)
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

        <!-- Resumen por Mes -->
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-calendar"></i> Ventas por Mes
                </h6>
            </div>
            <div class="card-body">
                @if($ventasPorMes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($ventasPorMes as $mes)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $mes->mes }}/{{ $mes->anio }}</span>
                            <div class="text-end">
                                <strong>S/ {{ number_format($mes->total_ingresos, 2) }}</strong><br>
                                <small class="text-muted">
                                    {{ $mes->total_ventas }} ventas 
                                    ({{ $mes->ventas_pagadas }} pagadas)
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No hay datos mensuales</p>
                @endif
            </div>
        </div>

        <!-- Información de Pagos -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-credit-card"></i> Información de Pagos
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Próxima Liquidación</h6>
                    <p class="mb-1">
                        <strong>Fecha estimada:</strong> 
                        {{ now()->addDays(5)->format('d/m/Y') }}
                    </p>
                    <p class="mb-0">
                        <strong>Monto estimado:</strong> 
                        S/ {{ number_format($ventas->where('estado_pago', 'pendiente')->sum('total_vendedor'), 2) }}
                    </p>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <h6>Comisiones</h6>
                    <p class="mb-1">
                        <strong>Tasa actual:</strong> {{ $ventas->first()->comision_porcentaje ?? '10' }}%
                    </p>
                    <p class="mb-0">
                        <strong>Total comisiones:</strong> 
                        S/ {{ number_format($ventas->sum(function($v) { return ($v->precio_venta * $v->cantidad * $v->comision_porcentaje) / 100; }), 2) }}
                    </p>
                </div>

                <div class="alert alert-info mt-3">
                    <small>
                        <i class="fa fa-info-circle"></i>
                        Los pagos se procesan mensualmente. Contacta al administrador para más información.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resumen Ejecutivo -->
<div class="card shadow mt-4">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-chart-bar"></i> Resumen Ejecutivo
        </h6>
    </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <h4>{{ $ventas->sum('cantidad') }}</h4>
                    <p class="text-muted mb-0">Unidades Vendidas</p>
                </div>
                <div class="col-md-3">
                    <h4>{{ $productosVendidos->count() }}</h4>
                    <p class="text-muted mb-0">Productos Activos</p>
                </div>
                <div class="col-md-3">
                    <h4>S/ {{ number_format($ventas->where('estado_pago', 'pagado')->avg('precio_venta'), 2) }}</h4>
                    <p class="text-muted mb-0">Precio Promedio (Pagados)</p>
                </div>
                <div class="col-md-3">
                    <h4>{{ number_format($ventas->where('estado_pago', 'pagado')->count() / max($ventas->count(), 1) * 100, 1) }}%</h4>
                    <p class="text-muted mb-0">Tasa de Pago</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .btn, .card-header .badge, .navbar-top, .vendedor-sidebar {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: #f8f9fa !important;
            color: #000 !important;
            border-bottom: 1px solid #ddd !important;
        }
    }
</style>
@endpush