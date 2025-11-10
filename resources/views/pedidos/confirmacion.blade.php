@extends('layouts.app')

@section('title', 'Pedido Confirmado')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="text-center mb-5">
            <i class="fa fa-check-circle fa-5x text-success mb-3"></i>
            <h1 class="display-4 text-success">¡Pedido Confirmado!</h1>
            <p class="lead">Tu pedido ha sido procesado exitosamente</p>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-info-circle"></i> Información del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Número de Pedido:</strong> #{{ $pedido->id }}</p>
                        <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-warning">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Método de Pago:</strong> {{ ucfirst($pedido->metodo_pago) }}</p>
                        <p><strong>Total:</strong> S/ {{ number_format($pedido->total, 2) }}</p>
                        @if($pedido->fecha_entrega)
                            <p><strong>Entrega estimada:</strong> {{ $pedido->fecha_entrega->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fa fa-list"></i> Detalles del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Vendedor</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->nombre }}</td>
                                    <td>
                                        @if($detalle->producto->vendedor)
                                            {{ $detalle->producto->vendedor->nombre }}
                                        @else
                                            <span class="text-muted">Admin</span>
                                        @endif
                                    </td>
                                    <td>{{ $detalle->cantidad }} {{ $detalle->producto->unidad }}</td>
                                    <td>S/ {{ number_format($detalle->precio, 2) }}</td>
                                    <td>S/ {{ number_format($detalle->precio * $detalle->cantidad, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-success">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td><strong>S/ {{ number_format($pedido->total, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        @if(!empty($pedido->notas))
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fa fa-sticky-note"></i> Notas del Pedido</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $pedido->notas }}</p>
            </div>
        </div>
        @endif
        
        <div class="text-center mt-4">
            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-success me-2">
                <i class="fa fa-history"></i> Ver Historial de Pedidos
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-success">
                <i class="fa fa-shopping-bag"></i> Seguir Comprando
            </a>
        </div>
    </div>
</div>
@endsection