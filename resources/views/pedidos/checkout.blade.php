@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2><i class="fa fa-credit-card"></i> Finalizar Compra</h2>

        <!-- Información del Pedido -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-list"></i> Resumen de tu Pedido</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Vendedor</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carrito as $item)
                                <tr>
                                    <td>{{ $item->producto->nombre }}</td>
                                    <td>
                                        @if($item->producto->vendedor)
                                            <small>{{ $item->producto->vendedor->nombre }}</small>
                                        @else
                                            <small class="text-muted">Admin</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->cantidad }} {{ $item->producto->unidad }}</td>
                                    <td>S/ {{ number_format($item->producto->precio, 2) }}</td>
                                    <td>S/ {{ number_format($item->producto->precio * $item->cantidad, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-success">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td><strong>S/ {{ number_format($total, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Formulario de Checkout -->
        <form method="POST" action="{{ route('pedidos.store') }}">
            @csrf
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fa fa-truck"></i> Información de Entrega</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="direccion_entrega" class="form-label">Dirección de Entrega *</label>
                        <textarea class="form-control @error('direccion_entrega') is-invalid @enderror" 
                                  id="direccion_entrega" name="direccion_entrega" 
                                  rows="3" required>{{ old('direccion_entrega', auth()->user()->direccion ?? '') }}</textarea>
                        @error('direccion_entrega')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Dirección donde entregaremos tu pedido</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Información de Contacto</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ auth()->user()->nombre }}" readonly>
                                <small class="form-text text-muted">Nombre</small>
                            </div>
                            <div class="col-md-6">
                                <input type="tel" class="form-control" value="{{ auth()->user()->telefono ?? 'No especificado' }}" readonly>
                                <small class="form-text text-muted">Teléfono</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fa fa-credit-card"></i> Método de Pago</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="efectivo" value="efectivo" checked>
                            <label class="form-check-label" for="efectivo">
                                <strong>Efectivo</strong> - Paga cuando recibas tu pedido
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="transferencia" value="transferencia">
                            <label class="form-check-label" for="transferencia">
                                <strong>Transferencia Bancaria</strong>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="tarjeta" value="tarjeta">
                            <label class="form-check-label" for="tarjeta">
                                <strong>Tarjeta de Crédito/Débito</strong>
                            </label>
                        </div>
                        @error('metodo_pago')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas Adicionales</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3" 
                                  placeholder="Instrucciones especiales para la entrega...">{{ old('notas') }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fa fa-check-circle"></i> Confirmar Pedido - S/ {{ number_format($total, 2) }}
                </button>
            </div>
        </form>
    </div>
    
    <div class="col-md-4">
        <!-- Resumen Lateral -->
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-receipt"></i> Resumen Final</h5>
            </div>
            <div class="card-body">
                <h6>Productos en tu pedido:</h6>
                <ul class="list-unstyled small mb-3">
                    @foreach($carrito as $item)
                        <li class="d-flex justify-content-between mb-2">
                            <div>
                                <span>{{ $item->producto->nombre }} (x{{ $item->cantidad }})</span>
                                @if($item->producto->vendedor)
                                    <br><small class="text-muted">{{ $item->producto->vendedor->nombre }}</small>
                                @endif
                            </div>
                            <span>S/ {{ number_format($item->producto->precio * $item->cantidad, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong class="h5 text-success">S/ {{ number_format($total, 2) }}</strong>
                </div>
                
                <div class="alert alert-info small">
                    <i class="fa fa-info-circle"></i> 
                    <strong>Entrega estimada:</strong> 24-48 horas<br>
                    <strong>Horario:</strong> Lunes a Sábado 9:00 - 18:00<br>
                    <strong>Envío:</strong> Gratis
                </div>
            </div>
        </div>
    </div>
</div>
@endsection