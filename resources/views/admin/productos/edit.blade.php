@extends('layouts.admin')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto: ' . $producto->nombre)

@section('content')
<!-- Notificaciones -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fa fa-exclamation-triangle"></i> Por favor corrige los siguientes errores:
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Editar Producto: <span class="text-success">{{ $producto->nombre }}</span></h2>
    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver a Productos
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-edit"></i> Editar Información del Producto
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.productos.update', $producto->id) }}" enctype="multipart/form-data" id="form-editar-producto">
                    @csrf
                    @method('PUT')
                    
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

                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vendedor_id" class="form-label">Vendedor</label>
                                <select class="form-select @error('vendedor_id') is-invalid @enderror" id="vendedor_id" name="vendedor_id">
                                    <option value="">Administrador (Sin vendedor)</option>
                                    @foreach($vendedores as $vendedor)
                                        <option value="{{ $vendedor->id }}" {{ old('vendedor_id', $producto->vendedor_id) == $vendedor->id ? 'selected' : '' }}>
                                            {{ $vendedor->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendedor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN DE IMAGEN DENTRO DEL FORMULARIO -->
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Cambiar Imagen</label>
                        <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                               id="imagen" name="imagen" 
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.bmp,.tiff" 
                               onchange="validarImagen(this)">
                        @error('imagen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Formatos permitidos: JPG, JPEG, PNG, GIF, WEBP, SVG, BMP, TIFF. Tamaño máximo: 2MB.
                            Deja en blanco para mantener la imagen actual.
                        </div>
                        
                        <!-- Vista previa de imagen -->
                        <div class="text-center mt-3">
                            <img id="preview" src="#" alt="Vista previa de la nueva imagen" 
                                 class="img-thumbnail mb-2" style="max-width: 100%; max-height: 200px; display: none;">
                            <p class="text-muted small" id="preview-text">
                                Vista previa de la nueva imagen aparecerá aquí
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado del Producto</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1"
                                           {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">Producto activo</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Aprobación</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="aprobado" name="aprobado" value="1"
                                           {{ old('aprobado', $producto->aprobado) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aprobado">Producto aprobado</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fa fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-save"></i> Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Imagen Actual -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-image"></i> Imagen Actual
                </h6>
            </div>
            <div class="card-body text-center">
                <img src="{{ $producto->imagen_url ?: config('app.placeholder_image') }}" 
                     alt="{{ $producto->nombre }}" 
                     class="img-thumbnail mb-3" style="max-width: 100%;">
                <p class="text-muted small mb-0">
                    {{ $producto->imagen ? 'Imagen actual del producto' : 'Sin imagen asignada' }}
                </p>
            </div>
        </div>

        <!-- Información del Producto -->
        <div class="card shadow mt-4">
            <div class="card-header bg-light">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-info-circle"></i> Información del Producto
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Vendedor:</strong><br>
                    @if($producto->vendedor)
                        <span class="badge bg-primary">{{ $producto->vendedor->nombre }}</span>
                    @else
                        <span class="badge bg-secondary">Administrador</span>
                    @endif
                </div>
                <div class="mb-3">
                    <strong>Fecha de creación:</strong><br>
                    {{ $producto->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Última actualización:</strong><br>
                    {{ $producto->updated_at->format('d/m/Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Estado actual:</strong><br>
                    <span class="badge bg-{{ $producto->activo ? 'success' : 'secondary' }}">
                        {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="badge bg-{{ $producto->aprobado ? 'primary' : 'warning' }}">
                        {{ $producto->aprobado ? 'Aprobado' : 'Pendiente' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Función para validar la imagen antes de subir
function validarImagen(input) {
    const file = input.files[0];
    const preview = document.getElementById('preview');
    const previewText = document.getElementById('preview-text');
    
    // Resetear estados
    input.classList.remove('is-invalid');
    preview.style.display = 'none';
    previewText.style.display = 'block';
    
    if (!file) return;
    
    // Validar tipo de archivo
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp', 'image/tiff'];
    if (!allowedTypes.includes(file.type)) {
        input.classList.add('is-invalid');
        alert('Formato de imagen no permitido. Use JPG, PNG, GIF, WEBP, SVG, BMP o TIFF.');
        input.value = '';
        return;
    }
    
    // Validar tamaño (2MB = 2 * 1024 * 1024 bytes)
    const maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
        input.classList.add('is-invalid');
        alert('La imagen es demasiado grande. Máximo 2MB permitido.');
        input.value = '';
        return;
    }
    
    // Mostrar vista previa
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
        previewText.style.display = 'none';
    }
    reader.readAsDataURL(file);
}

// Vista previa de imagen
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