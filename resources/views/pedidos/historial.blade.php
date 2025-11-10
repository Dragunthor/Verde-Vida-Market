@extends('layouts.app')

@section('title', 'Historial de Pedidos')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fa fa-history"></i> Mis Pedidos</h2>
        
        @if($pedidos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
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
                                    <a href="{{ route('pedidos.show', $pedido->id) }}" 
                                       class="btn btn-sm btn-outline-success">
                                        <i class="fa fa-eye"></i> Ver
                                    </a>
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
                <p class="text-muted mb-4">¡Aún no has realizado ningún pedido!</p>
                <a href="{{ route('productos.index') }}" class="btn btn-success btn-lg">
                    <i class="fa fa-shopping-cart"></i> Comenzar a Comprar
                </a>
            </div>
        @endif
    </div>
</div>
@endsection