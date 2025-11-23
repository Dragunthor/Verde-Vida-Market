@extends('layouts.vendedor')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto: ' . $producto->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Editar Producto</h2>
    <a href="{{ route('vendedor.productos') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver a Mis Productos
    </a>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-edit"></i> Editar Información del Producto
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('vendedor.productos.actualizar', $producto->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Información Básica -->
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                          id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="precio" class="form-label">Precio (S/) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('precio') is-invalid @enderror" 
                                               id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" 
                                               step="0.01" min="0" required>
                                        @error('precio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                               id="stock" name="stock" value="{{ old('stock', $producto->stock) }}" 
                                               min="0" required>
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unidad" class="form-label">Unidad de Medida <span class="text-danger">*</span></label>
                                        <select class="form-select @error('unidad') is-invalid @enderror" id="unidad" name="unidad" required>
                                            <option value="">Seleccionar unidad</option>
                                            <option value="kg" {{ old('unidad', $producto->unidad) == 'kg' ? 'selected' : '' }}>Kilogramo (kg)</option>
                                            <option value="g" {{ old('unidad', $producto->unidad) == 'g' ? 'selected' : '' }}>Gramo (g)</option>
                                            <option value="L" {{ old('unidad', $producto->unidad) == 'L' ? 'selected' : '' }}>Litro (L)</option>
                                            <option value="ml" {{ old('unidad', $producto->unidad) == 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                                            <option value="unidad" {{ old('unidad', $producto->unidad) == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                            <option value="paquete" {{ old('unidad', $producto->unidad) == 'paquete' ? 'selected' : '' }}>Paquete</option>
                                            <option value="docena" {{ old('unidad', $producto->unidad) == 'docena' ? 'selected' : '' }}>Docena</option>
                                            <option value="atado" {{ old('unidad', $producto->unidad) == 'atado' ? 'selected' : '' }}>Atado</option>
                                            <option value="bandeja" {{ old('unidad', $producto->unidad) == 'bandeja' ? 'selected' : '' }}>Bandeja</option>
                                        </select>
                                        @error('unidad')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="origen" class="form-label">Origen <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('origen') is-invalid @enderror" 
                                               id="origen" name="origen" value="{{ old('origen', $producto->origen) }}" required>
                                        @error('origen')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="categoria_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                                <select class="form-select @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                                           {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">Producto activo</label>
                                </div>
                                <div class="form-text">Los productos inactivos no se mostrarán en la tienda</div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Sección de Imagen DENTRO del formulario -->
                            <div class="card shadow">
                                <div class="card-header bg-info text-white">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fa fa-image"></i> Imagen del Producto
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    @if($producto->imagen)
                                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                             alt="{{ $producto->nombre }}" 
                                             class="img-thumbnail mb-3" style="max-width: 100%;">
                                        <p class="text-muted small">Imagen actual</p>
                                    @else
                                        <div class="text-muted mb-3">
                                            <i class="fa fa-image fa-3x"></i>
                                            <p class="mt-2">No hay imagen actual</p>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="imagen" class="form-label">Cambiar Imagen</label>
                                        <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                               id="imagen" name="imagen" accept="image/*">
                                        @error('imagen')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Formatos: JPG, PNG, GIF, WEBP. Tamaño máximo: 2MB.
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <img id="preview" src="#" alt="Vista previa" 
                                             class="img-thumbnail mt-3" style="max-width: 100%; display: none;">
                                        <p class="text-muted small" id="preview-text">
                                            La nueva imagen aparecerá aquí
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-4">
                        <h6><i class="fa fa-exclamation-triangle"></i> Importante</h6>
                        <p class="mb-0">
                            Al editar un producto, este volverá a estado "Pendiente de aprobación" y 
                            requerirá nueva revisión del administrador antes de aparecer en la tienda.
                        </p>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('vendedor.productos') }}" class="btn btn-outline-secondary me-2">
                            <i class="fa fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Información del Producto (FUERA del formulario) -->
<div class="card shadow mt-4">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-info-circle"></i> Información del Producto
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2">
                    <strong>Estado Actual:</strong><br>
                    @if($producto->aprobado)
                        <span class="badge bg-success">
                            <i class="fa fa-check"></i> Aprobado
                        </span>
                    @else
                        <span class="badge bg-warning">
                            <i class="fa fa-clock-o"></i> Pendiente
                        </span>
                    @endif
                </div>
                
                <div class="mb-2">
                    <strong>Fecha de Creación:</strong><br>
                    {{ $producto->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <strong>Última Actualización:</strong><br>
                    {{ $producto->updated_at->format('d/m/Y H:i') }}
                </div>
                
                @if($producto->aprobado && $producto->activo)
                    <div class="alert alert-success mt-3">
                        <i class="fa fa-check"></i> 
                        Este producto está actualmente visible en la tienda
                    </div>
                @else
                    <div class="alert alert-warning mt-3">
                        <i class="fa fa-info-circle"></i> 
                        Este producto no está visible en la tienda
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('imagen').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const previewText = document.getElementById('preview-text');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                previewText.style.display = 'none';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            previewText.style.display = 'block';
        }
    });
</script>
@endpush
@endsection
