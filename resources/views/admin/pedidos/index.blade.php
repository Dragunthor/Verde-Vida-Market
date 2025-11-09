@extends('layouts.admin')

@section('title', 'Gestión de Pedidos')

@section('content')
<h2 class="mb-4">Gestión de Pedidos</h2>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-list"></i> Lista de Pedidos
        </h6>
    </div>
    <div class="card-body">
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
                        <td>#{{ $pedido['id'] }}</td>
                        <td>{{ $pedido['cliente_nombre'] }}</td>
                        <td>{{ $pedido['fecha_pedido'] }}</td>
                        <td>S/ {{ number_format($pedido['total'], 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $pedido['estado'] == 'pendiente' ? 'warning' : ($pedido['estado'] == 'confirmado' ? 'info' : 'success') }}">
                                {{ ucfirst($pedido['estado']) }}
                            </span>
                        </td>
                        <td>{{ ucfirst($pedido['metodo_pago']) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.pedidos.gestionar', $pedido['id']) }}" 
                                   class="btn btn-outline-primary" title="Gestionar">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection