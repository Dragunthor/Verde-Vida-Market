@extends('layouts.app')

@section('title', 'Mi Carrito')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2><i class="fa fa-shopping-cart"></i> Mi Carrito de Compras</h2>
        
        @if($carrito->count() > 0)
            <!-- Formulario principal para actualizar cantidades -->
            <form method="POST" action="{{ route('carrito.actualizar-todo') }}" id="form-actualizar-carrito">
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
                                <tr data-producto-precio="{{ $item->producto->precio }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->producto->imagen_url ?: config('app.placeholder_image') }}" 
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
                                        S/ <span class="precio-unitario">{{ number_format($item->producto->precio, 2) }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <input type="number" name="cantidades[{{ $item->id }}]" 
                                               value="{{ $item->cantidad }}" 
                                               min="1" max="{{ $item->producto->stock }}" 
                                               class="form-control input-cantidad" style="width: 80px;"
                                               data-item-id="{{ $item->id }}">
                                        <small class="text-muted stock-info" data-stock="{{ $item->producto->stock }}">
                                            Disponible: {{ $item->producto->stock }}
                                        </small>
                                    </td>
                                    <td class="align-middle">
                                        <strong class="subtotal">S/ {{ number_format($item->producto->precio * $item->cantidad, 2) }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="eliminarItem({{ $item->id }}, '{{ $item->producto->nombre }}')">
                                            <i class="fa fa-trash"></i>
                                        </button>
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
                        <span id="resumen-subtotal">S/ {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Envío:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total:</strong>
                        <strong class="h5 text-success" id="resumen-total">S/ {{ number_format($subtotal, 2) }}</strong>
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

{{-- Sección de acciones --}}
@if($carrito->count() > 0)
<div class="row mt-4">
    <div class="col-md-8">
        <a href="{{ route('productos.index') }}" class="btn btn-outline-success">
            <i class="fa fa-arrow-left"></i> Seguir Comprando
        </a>
    </div>
    <div class="col-md-4 text-end">
        @auth
            <a href="{{ route('pedidos.checkout') }}" class="btn btn-success btn-lg">
                <i class="fa fa-credit-card"></i> Proceder al Pago
            </a>
        @else
            <div class="d-grid gap-2">
                <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                    <i class="fa fa-sign-in"></i> Iniciar Sesión para Comprar
                </a>
                <small class="text-muted">O <a href="{{ route('register') }}">regístrate</a> si no tienes cuenta</small>
            </div>
        @endauth
    </div>
</div>
@endif

{{-- Formularios ocultos para eliminar items --}}
@foreach($carrito as $item)
<form method="POST" action="{{ route('carrito.eliminar', $item->id) }}" id="form-eliminar-{{ $item->id }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

<script>
// Función para eliminar items del carrito
function eliminarItem(itemId, productoNombre) {
    if (confirm(`¿Estás seguro de eliminar "${productoNombre}" del carrito?`)) {
        document.getElementById(`form-eliminar-${itemId}`).submit();
    }
}

// Función para actualizar los subtotales y el total
function actualizarTotales() {
    let subtotalGeneral = 0;

    // Recorrer cada fila de la tabla
    document.querySelectorAll('tbody tr').forEach(fila => {
        const precio = parseFloat(fila.getAttribute('data-producto-precio'));
        const inputCantidad = fila.querySelector('.input-cantidad');
        const cantidad = parseInt(inputCantidad.value) || 0;
        const subtotalElemento = fila.querySelector('.subtotal');
        const stockInfo = fila.querySelector('.stock-info');
        const stockMaximo = parseInt(stockInfo.getAttribute('data-stock'));

        // Calcular subtotal de la fila
        const subtotalFila = precio * cantidad;
        subtotalElemento.textContent = `S/ ${subtotalFila.toFixed(2)}`;
        
        // Actualizar información de stock disponible
        const restantes = stockMaximo - cantidad;
        stockInfo.textContent = `Disponible: ${restantes}`;
        
        // Cambiar color si quedan pocas unidades
        if (restantes <= 3) {
            stockInfo.className = 'stock-info text-danger small';
        } else if (restantes <= 10) {
            stockInfo.className = 'stock-info text-warning small';
        } else {
            stockInfo.className = 'stock-info text-muted small';
        }

        // Acumular para el subtotal general
        subtotalGeneral += subtotalFila;
    });

    // Actualizar el resumen en la tarjeta lateral
    document.getElementById('resumen-subtotal').textContent = `S/ ${subtotalGeneral.toFixed(2)}`;
    document.getElementById('resumen-total').textContent = `S/ ${subtotalGeneral.toFixed(2)}`;
}

// Evento cuando cambia la cantidad en cualquier input
document.querySelectorAll('.input-cantidad').forEach(input => {
    input.addEventListener('change', function() {
        const maxStock = parseInt(this.getAttribute('max'));
        const cantidad = parseInt(this.value) || 0;

        // Validar que la cantidad esté dentro del stock
        if (cantidad < 1) {
            this.value = 1;
        } else if (cantidad > maxStock) {
            this.value = maxStock;
            alert(`Solo hay ${maxStock} unidades disponibles`);
        }

        // Actualizar los totales en tiempo real
        actualizarTotales();
    });

    input.addEventListener('input', function() {
        // Actualizar en tiempo real mientras el usuario escribe
        actualizarTotales();
    });
});

// Inicializar los totales al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarTotales();
});

// Función para vaciar el carrito
function vaciarCarrito() {
    if (confirm('¿Estás seguro de vaciar todo el carrito?')) {
        document.getElementById('form-vaciar-carrito').submit();
    }
}
</script>

{{-- Formulario oculto para vaciar carrito --}}
<form method="POST" action="{{ route('carrito.vaciar') }}" id="form-vaciar-carrito" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection