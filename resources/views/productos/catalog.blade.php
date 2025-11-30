@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-filter"></i> Filtrar Productos</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('productos.index') }}">
                    <div class="mb-3">
                        <label for="busqueda" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="busqueda" name="busqueda" 
                               value="{{ request('busqueda') }}" 
                               placeholder="Nombre del producto...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" 
                                    {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vendedor" class="form-label">Vendedor</label>
                        <select class="form-select" id="vendedor" name="vendedor">
                            <option value="">Todos los vendedores</option>
                            @foreach($vendedores as $vendedor)
                                <option value="{{ $vendedor->id }}" 
                                    {{ request('vendedor') == $vendedor->id ? 'selected' : '' }}>
                                    {{ $vendedor->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa fa-search"></i> Aplicar Filtros
                    </button>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fa fa-refresh"></i> Limpiar
                    </a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <!-- Encabezado y resultados -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Catálogo de Productos</h2>
            <span class="text-muted">{{ $productos->total() }} productos encontrados</span>
        </div>

        @if($productos->count() > 0)
        <!-- Productos -->
        <div class="row">
            @foreach($productos as $producto)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 product-card">
                        <img src="{{ $producto->imagen_url ?: config('app.placeholder_image') }}" 
                             class="card-img-top" alt="{{ $producto->nombre }}" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $producto->nombre }}</h5>
                            
                            @if($producto->vendedor && $producto->vendedor->perfilVendedor)
                                <small class="text-muted mb-2">
                                    Vendido por: {{ $producto->vendedor->nombre }}
                                </small>
                            @endif
                            
                            <p class="card-text flex-grow-1 text-muted small">
                                {{ Str::limit($producto->descripcion, 100) }}
                            </p>
                            
                            <div class="mt-auto">
                                <p class="text-success fw-bold h5 mb-2">
                                    S/ {{ number_format($producto->precio, 2) }}
                                    <small class="text-muted">/{{ $producto->unidad }}</small>
                                </p>
                                
                                <!-- Controles de cantidad y acciones -->
                                <div class="row align-items-center g-2 mb-2">
                                    <div class="col-4">
                                        <a href="{{ route('productos.show', $producto->id) }}" 
                                           class="btn btn-outline-success btn-sm w-100">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                    
                                    @if($producto->estaDisponible())
                                        <form method="POST" action="{{ route('carrito.agregar') }}" class="col-8">
                                            @csrf
                                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <input type="number" class="form-control form-control-sm text-center" 
                                                           name="cantidad" value="1" min="1" 
                                                           max="{{ $producto->stock }}" 
                                                           aria-label="Cantidad">
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit" class="btn btn-success btn-sm w-100" 
                                                            title="Agregar al carrito">
                                                        <i class="fa fa-cart-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <div class="col-8">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <input type="number" class="form-control form-control-sm text-center" 
                                                           value="0" disabled aria-label="Cantidad">
                                                </div>
                                                <div class="col-6">
                                                    <button class="btn btn-secondary btn-sm w-100" disabled>
                                                        Sin Stock
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Información de stock -->
                                <div class="text-center">
                                    <small class="text-muted">
                                        Stock: {{ $producto->stock }} {{ $producto->unidad }}
                                    </small>
                                    @if($producto->vendedor && $producto->vendedor->perfilVendedor)
                                        <br>
                                        <small class="text-muted">
                                            Calificación: 
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $producto->calificacionPromedio())
                                                    <i class="fa fa-star text-warning"></i>
                                                @else
                                                    <i class="fa fa-star-o text-muted"></i>
                                                @endif
                                            @endfor
                                            ({{ $producto->totalResenas() }})
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $productos->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fa fa-search fa-3x text-muted mb-3"></i>
            <h4>No se encontraron productos</h4>
            <p class="text-muted">Intenta con otros filtros de búsqueda</p>
            <a href="{{ route('productos.index') }}" class="btn btn-success">Ver todos los productos</a>
        </div>
        @endif
    </div>
</div>
@endsection