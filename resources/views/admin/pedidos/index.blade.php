@extends('layouts.admin')

@section('title', 'Gestión de Pedidos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Pedidos</h2>
</div>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-list"></i> Lista de Pedidos
        </h6>
    </div>
    <div class="card-body">
        @if($pedidos->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Pedido #</th>
                        <th>Cliente</th>
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
                        <td>{{ $pedido->usuario->nombre }}</td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td>S/ {{ number_format($pedido->total, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $pedido->estado == 'pendiente' ? 'warning' : ($pedido->estado == 'confirmado' ? 'info' : ($pedido->estado == 'entregado' ? 'success' : ($pedido->estado == 'cancelado' ? 'danger' : 'secondary'))) }}">
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
            <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
            <p class="text-muted">No hay pedidos registrados.</p>
        </div>
        @endif
    </div>
</div>
@endsection