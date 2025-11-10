@extends('layouts.app')

@section('title', 'Mi Carrito')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2><i class="fa fa-shopping-cart"></i> Mi Carrito de Compras</h2>
        
        @if($carrito->count() > 0)
            <form method="POST" action="{{ route('carrito.actualizar-todo') }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-success">
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carrito as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->producto->imagen ? asset('storage/' . $item->producto->imagen) : asset('images/placeholder.jpg') }}" 
                                                 alt="{{ $item->producto->nombre }}" 
                                                 class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">{{ $item->producto->nombre }}</h6>
                                                <small class="text-muted">{{ $item->producto->unidad }}</small>
                                                @if($item->producto->vendedor)
                                                    <br>
                                                    <small class="text-muted">
                                                        Vendido por: {{ $item->producto->vendedor->nombre }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        S/ {{ number_format($item->producto->precio, 2) }}
                                    </td>
                                    <td class="align-middle">
                                        <input type="number" name="cantidades[{{ $item->id }}]" 
                                               value="{{ $item->cantidad }}" 
                                               min="1" max="{{ $item->producto->stock }}" 
                                               class="form-control" style="width: 80px;"
                                               onchange="this.form.submit()">
                                        <small class="text-muted">Máx: {{ $item->producto->stock }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <strong>S/ {{ number_format($item->producto->precio * $item->cantidad, 2) }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        <form method="POST" action="{{ route('carrito.eliminar', $item->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('¿Estás seguro de eliminar este producto del carrito?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between mb-4">
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-success">
                        <i class="fa fa-arrow-left"></i> Seguir Comprando
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-refresh"></i> Actualizar Carrito
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-5">
                <i class="fa fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h3>Tu carrito está vacío</h3>
                <p class="text-muted mb-4">¡Descubre nuestros deliciosos productos orgánicos!</p>
                <a href="{{ route('productos.index') }}" class="btn btn-success btn-lg">
                    <i class="fa fa-shopping-bag"></i> Comprar Ahora
                </a>
            </div>
        @endif
    </div>
    
    <div class="col-md-4">
        @if($carrito->count() > 0)
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fa fa-receipt"></i> Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    @php
                        $subtotal = 0;
                        foreach($carrito as $item) {
                            $subtotal += $item->producto->precio * $item->cantidad;
                        }
                    @endphp
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span>S/ {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Envío:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total:</strong>
                        <strong class="h5 text-success">S/ {{ number_format($subtotal, 2) }}</strong>
                    </div>
                    
                    @auth
                        <a href="{{ route('pedidos.checkout') }}" class="btn btn-success w-100 btn-lg">
                            <i class="fa fa-credit-card"></i> Proceder al Pago
                        </a>
                        <small class="text-muted d-block text-center mt-2">
                            <i class="fa fa-lock"></i> Pago seguro
                        </small>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-2"><strong>¿Ya tienes cuenta?</strong></p>
                            <a href="{{ route('login') }}?redirect=carrito" class="btn btn-outline-success btn-sm w-100 mb-2">
                                Iniciar Sesión
                            </a>
                            <p class="mb-2 mt-3"><strong>¿Nuevo cliente?</strong></p>
                            <a href="{{ route('register') }}" class="btn btn-success btn-sm w-100">
                                Crear Cuenta
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        @endif
    </div>
</div>

@if($carrito->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fa fa-info-circle"></i> Información Importante</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><i class="fa fa-check text-success me-2"></i> Los precios incluyen IGV</li>
                    <li><i class="fa fa-check text-success me-2"></i> Entrega en 24-48 horas</li>
                    <li><i class="fa fa-check text-success me-2"></i> Productos frescos garantizados</li>
                    <li><i class="fa fa-check text-success me-2"></i> Soporte al cliente 24/7</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
@endsection