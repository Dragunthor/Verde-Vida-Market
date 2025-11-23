@extends('layouts.vendedor')

@section('title', 'Mis Pedidos')
@section('page-title', 'Gestión de Mis Pedidos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Mis Pedidos</h2>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fa fa-filter"></i> Filtrar por Estado
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['estado' => '']) }}">Todos los pedidos</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['estado' => 'pendiente']) }}">Pendientes</a></li>
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['estado' => 'confirmado']) }}">Confirmados</a></li>
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['estado' => 'preparando']) }}">Preparando</a></li>
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['estado' => 'listo']) }}">Listos para entrega</a></li>
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['estado' => 'entregado']) }}">Entregados</a></li>
            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['estado' => 'cancelado']) }}">Cancelados</a></li>
        </ul>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $ventas->count() }}</h4>
                        <p class="mb-0">Total Pedidos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $ventas->where('pedido.estado', 'pendiente')->count() }}</h4>
                        <p class="mb-0">Pendientes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-clock-o fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $ventas->where('pedido.estado', 'preparando')->count() }}</h4>
                        <p class="mb-0">En Proceso</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-cogs fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $ventas->where('pedido.estado', 'entregado')->count() }}</h4>
                        <p class="mb-0">Entregados</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-list"></i> Pedidos con Mis Productos
        </h6>
        <span class="badge bg-light text-dark">{{ $ventas->count() }} pedidos</span>
    </div>
    <div class="card-body">
        @if($ventas->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Pedido #</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)
                    <tr>
                        <td><strong>#{{ $venta->pedido->id }}</strong></td>
                        <td>{{ $venta->pedido->usuario->nombre }}</td>
                        <td>
                            <strong>{{ $venta->producto->nombre }}</strong><br>
                            <small class="text-muted">{{ $venta->producto->unidad }}</small>
                        </td>
                        <td>{{ $venta->cantidad }}</td>
                        <td>
                            <strong>S/ {{ number_format($venta->precio_venta * $venta->cantidad, 2) }}</strong><br>
                            <small class="text-muted">Vendedor: S/ {{ number_format($venta->total_vendedor, 2) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $venta->pedido->estado == 'pendiente' ? 'warning' : ($venta->pedido->estado == 'confirmado' ? 'info' : ($venta->pedido->estado == 'preparando' ? 'primary' : ($venta->pedido->estado == 'listo' ? 'secondary' : ($venta->pedido->estado == 'entregado' ? 'success' : 'danger')))) }}">
                                {{ ucfirst($venta->pedido->estado) }}
                            </span>
                        </td>
                        <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detalleModal{{ $venta->id }}"
                                        title="Ver detalle">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>

                            <!-- Modal de Detalle -->
                            <div class="modal fade" id="detalleModal{{ $venta->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">Detalle del Pedido #{{ $venta->pedido->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Información del Pedido</h6>
                                                    <p><strong>Estado:</strong> 
                                                        <span class="badge bg-{{ $venta->pedido->estado == 'pendiente' ? 'warning' : ($venta->pedido->estado == 'confirmado' ? 'info' : ($venta->pedido->estado == 'preparando' ? 'primary' : ($venta->pedido->estado == 'listo' ? 'secondary' : ($venta->pedido->estado == 'entregado' ? 'success' : 'danger')))) }}">
                                                            {{ ucfirst($venta->pedido->estado) }}
                                                        </span>
                                                    </p>
                                                    <p><strong>Fecha:</strong> {{ $venta->pedido->created_at->format('d/m/Y H:i') }}</p>
                                                    <p><strong>Método Pago:</strong> {{ ucfirst($venta->pedido->metodo_pago) }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Información del Cliente</h6>
                                                    <p><strong>Nombre:</strong> {{ $venta->pedido->usuario->nombre }}</p>
                                                    <p><strong>Email:</strong> {{ $venta->pedido->usuario->email }}</p>
                                                    <p><strong>Teléfono:</strong> {{ $venta->pedido->usuario->telefono ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            <h6>Producto Vendido</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Producto</th>
                                                            <th>Cantidad</th>
                                                            <th>Precio Unit.</th>
                                                            <th>Subtotal</th>
                                                            <th>Comisión</th>
                                                            <th>Total Vendedor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    @if($venta->producto->imagen)
                                                                        <img src="{{ asset('storage/' . $venta->producto->imagen) }}" 
                                                                             alt="{{ $venta->producto->nombre }}" 
                                                                             class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                                    @endif
                                                                    <div>
                                                                        <strong>{{ $venta->producto->nombre }}</strong><br>
                                                                        <small class="text-muted">{{ $venta->producto->unidad }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $venta->cantidad }}</td>
                                                            <td>S/ {{ number_format($venta->precio_venta, 2) }}</td>
                                                            <td>S/ {{ number_format($venta->precio_venta * $venta->cantidad, 2) }}</td>
                                                            <td>{{ $venta->comision_porcentaje }}%</td>
                                                            <td><strong>S/ {{ number_format($venta->total_vendedor, 2) }}</strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            @if($venta->pedido->notas)
                                                <div class="mt-3">
                                                    <h6>Notas del Pedido</h6>
                                                    <p class="text-muted">{{ $venta->pedido->notas }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
            <i class="fa fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4>No tienes pedidos</h4>
            <p class="text-muted">Tus productos aparecerán aquí cuando los clientes realicen pedidos.</p>
            <a href="{{ route('vendedor.productos') }}" class="btn btn-success">
                <i class="fa fa-cubes"></i> Gestionar Mis Productos
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Información Importante -->
<div class="card shadow mt-4">
    <div class="card-header bg-info text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-info-circle"></i> Información para Vendedores
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Proceso de Pedidos</h6>
                <ul class="list-unstyled small">
                    <li><i class="fa fa-clock-o text-warning me-2"></i> <strong>Pendiente:</strong> El cliente ha realizado el pedido</li>
                    <li><i class="fa fa-check text-info me-2"></i> <strong>Confirmado:</strong> El pedido ha sido confirmado</li>
                    <li><i class="fa fa-cogs text-primary me-2"></i> <strong>Preparando:</strong> El producto está siendo preparado</li>
                    <li><i class="fa fa-truck text-secondary me-2"></i> <strong>Listo:</strong> Listo para entrega/recogida</li>
                    <li><i class="fa fa-check-circle text-success me-2"></i> <strong>Entregado:</strong> Pedido completado</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Sistema de Pagos</h6>
                <ul class="list-unstyled small">
                    <li><i class="fa fa-percent text-warning me-2"></i> <strong>Comisión:</strong> {{ $ventas->first()->comision_porcentaje ?? '10' }}% por venta</li>
                    <li><i class="fa fa-money text-success me-2"></i> <strong>Pagos:</strong> Se procesan al completar el pedido</li>
                    <li><i class="fa fa-calendar text-info me-2"></i> <strong>Liquidaciones:</strong> Mensuales o según acuerdo</li>
                    <li><i class="fa fa-file-text text-primary me-2"></i> <strong>Reportes:</strong> Disponibles en la sección de Ventas</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection