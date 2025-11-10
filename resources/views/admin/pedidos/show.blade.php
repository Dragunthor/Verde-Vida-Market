@extends('layouts.admin')

@section('title', 'Gestionar Pedido')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestionar Pedido #{{ $pedidoData['id'] }}</h2>
    <a href="{{ route('admin.pedidos') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Detalles del Pedido -->
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-list"></i> Detalles del Pedido
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalles as $detalle)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $detalle['imagen']) }}" 
                                             alt="{{ $detalle['producto_nombre'] }}" 
                                             class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $detalle['producto_nombre'] }}</strong><br>
                                            <small class="text-muted">{{ $detalle['unidad'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $detalle['cantidad'] }}</td>
                                <td>S/ {{ number_format($detalle['precio'], 2) }}</td>
                                <td><strong>S/ {{ number_format($detalle['precio'] * $detalle['cantidad'], 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-success">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>S/ {{ number_format($pedidoData['total'], 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-user"></i> Información del Cliente
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> {{ $pedidoData['usuario_nombre'] }}</p>
                        <p><strong>Email:</strong> {{ $pedidoData['email'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Teléfono:</strong> {{ $pedidoData['telefono'] }}</p>
                        <p><strong>Dirección:</strong> {{ $pedidoData['direccion'] }}</p>
                    </div>
                </div>
                @if(!empty($pedidoData['notas']))
                    <hr>
                    <p><strong>Notas del Pedido:</strong></p>
                    <p class="text-muted">{{ $pedidoData['notas'] }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Gestión del Estado -->
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-cog"></i> Gestión del Pedido
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.pedidos.update', $pedidoData['id']) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado Actual</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="pendiente" {{ $pedidoData['estado'] == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmado" {{ $pedidoData['estado'] == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                            <option value="preparando" {{ $pedidoData['estado'] == 'preparando' ? 'selected' : '' }}>Preparando</option>
                            <option value="listo" {{ $pedidoData['estado'] == 'listo' ? 'selected' : '' }}>Listo para entrega</option>
                            <option value="entregado" {{ $pedidoData['estado'] == 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ $pedidoData['estado'] == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <p><strong>Información del Pedido:</strong></p>
                        <ul class="list-unstyled small">
                            <li><strong>Pedido #:</strong> {{ $pedidoData['id'] }}</li>
                            <li><strong>Fecha:</strong> {{ $pedidoData['fecha_pedido'] }}</li>
                            <li><strong>Método Pago:</strong> {{ ucfirst($pedidoData['metodo_pago']) }}</li>
                            <li><strong>Total:</strong> S/ {{ number_format($pedidoData['total'], 2) }}</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa fa-save"></i> Actualizar Estado
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection