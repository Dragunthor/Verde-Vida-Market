@extends('layouts.admin')

@section('title', 'Historial de Pedidos - ' . $cliente->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fa fa-history"></i> Historial de Pedidos 
        <small class="text-muted">- {{ $cliente->nombre }}</small>
    </h2>
    <div>
        <a href="{{ route('admin.clientes.show', $cliente->id) }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left"></i> Volver al Cliente
        </a>
        <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-secondary">
            <i class="fa fa-users"></i> Todos los Clientes
        </a>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $pedidos->total() }}</h4>
                        <p class="mb-0">Total Pedidos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-shopping-bag fa-2x"></i>
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
                        <h4>S/ {{ number_format($cliente->pedidos->where('estado', 'entregado')->sum('total'), 0) }}</h4>
                        <p class="mb-0">Total Gastado</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fa fa-money fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $cliente->pedidos->whereIn('estado', ['pendiente', 'confirmado'])->count() }}</h4>
                        <p class="mb-0">Pedidos Activos</p>
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
                        <h4>{{ $cliente->pedidos->where('estado', 'entregado')->count() }}</h4>
                        <p class="mb-0">Pedidos Entregados</p>
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
            <i class="fa fa-list"></i> Lista de Pedidos
        </h6>
        <span class="badge bg-light text-dark">{{ $pedidos->total() }} pedidos</span>
    </div>
    <div class="card-body">
        @if($pedidos->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>Pedido #</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Método Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                            <tr>
                                <td><strong>#{{ $pedido->id }}</strong></td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td>S/ {{ number_format($pedido->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $pedido->estado == 'pendiente' ? 'warning' : ($pedido->estado == 'entregado' ? 'success' : ($pedido->estado == 'cancelado' ? 'danger' : 'info')) }}">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($pedido->metodo_pago) }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.pedidos.show', $pedido->id) }}" 
                                           class="btn btn-outline-primary" title="Ver detalle">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if(in_array($pedido->estado, ['pendiente', 'confirmado']))
                                            <a href="{{ route('admin.pedidos.show', $pedido->id) }}#gestion" 
                                               class="btn btn-outline-warning" title="Gestionar">
                                                <i class="fa fa-cog"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $pedidos->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-shopping-bag fa-4x text-muted mb-3"></i>
                <h3>No hay pedidos</h3>
                <p class="text-muted mb-4">Este cliente aún no ha realizado ningún pedido.</p>
                <a href="{{ route('admin.clientes.show', $cliente->id) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i> Volver al Cliente
                </a>
            </div>
        @endif
    </div>
</div>
@endsection