@extends('layouts.app')

@section('title', 'Detalle del Pedido')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2><i class="fa fa-file-text"></i> Detalle del Pedido #{{ $pedido['id'] }}</h2>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-cube"></i> Productos del Pedido</h5>
            </div>
            <div class="card-body">
                @foreach($detalles as $detalle)
                    <div class="row mb-3 pb-3 border-bottom">
                        <div class="col-2">
                            <img src="{{ asset('images/' . $detalle['imagen']) }}" 
                                 alt="{{ $detalle['producto_nombre'] }}" 
                                 class="img-fluid rounded" style="max-height: 60px;">
                        </div>
                        <div class="col-6">
                            <h6 class="mb-1">{{ $detalle['producto_nombre'] }}</h6>
                            <small class="text-muted">Cantidad: {{ $detalle['cantidad'] }} {{ $detalle['unidad'] }}</small>
                        </div>
                        <div class="col-4 text-end">
                            <div class="h6 mb-0">S/ {{ number_format($detalle['precio'] * $detalle['cantidad'], 2) }}</div>
                            <small class="text-muted">S/ {{ number_format($detalle['precio'], 2) }} c/u</small>
                        </div>
                    </div>
                @endforeach
                
                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <h5>Total: S/ {{ number_format($pedido['total'], 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>
        
        @if(!empty($pedido['notas']))
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fa fa-sticky-note"></i> Notas del Pedido</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $pedido['notas'] }}</p>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fa fa-info-circle"></i> Información del Pedido</h5>
            </div>
            <div class="card-body">
                <p><strong>Estado:</strong><br>
                    <span class="badge bg-{{ $pedido['estado'] == 'pendiente' ? 'warning' : ($pedido['estado'] == 'entregado' ? 'success' : 'info') }}">
                        {{ ucfirst($pedido['estado']) }}
                    </span>
                </p>
                
                <p><strong>Fecha del Pedido:</strong><br>
                    {{ $pedido['fecha_pedido'] }}
                </p>
                
                @if($pedido['fecha_entrega'])
                <p><strong>Entrega Estimada:</strong><br>
                    {{ $pedido['fecha_entrega'] }}
                </p>
                @endif
                
                <p><strong>Método de Pago:</strong><br>
                    {{ ucfirst($pedido['metodo_pago']) }}
                </p>
                
                <hr>
                
                <h6>Información de Contacto</h6>
                <p class="mb-1"><strong>Nombre:</strong> {{ $pedido['usuario_nombre'] }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $pedido['email'] }}</p>
                <p class="mb-1"><strong>Teléfono:</strong> {{ $pedido['telefono'] }}</p>
                <p class="mb-0"><strong>Dirección:</strong> {{ $pedido['direccion'] }}</p>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <a href="{{ route('pedidos.historial') }}" class="btn btn-outline-success w-100">
                <i class="fa fa-arrow-left"></i> Volver al Historial
            </a>
        </div>
    </div>
</div>
@endsection