@extends('layouts.admin')

@section('title', 'Crear Producto')
@section('page-title', 'Crear Nuevo Producto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Crear Nuevo Producto</h2>
    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver a Productos
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-plus-circle"></i> Información del Producto
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data" id="form-crear-producto">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre') }}" required 
                               placeholder="Ej: Manzanas Orgánicas, Queso Fresco...">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="4" 
                                  placeholder="Describe las características y beneficios del producto..."
                                  required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio (S/) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('precio') is-invalid @enderror" 
                                       id="precio" name="precio" value="{{ old('precio') }}" 
                                       step="0.01" min="0" required placeholder="0.00">
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock') }}" 
                                       min="0" required placeholder="0">
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
                                    <option value="kg" {{ old('unidad') == 'kg' ? 'selected' : '' }}>Kilogramo (kg)</option>
                                    <option value="g" {{ old('unidad') == 'g' ? 'selected' : '' }}>Gramo (g)</option>
                                    <option value="L" {{ old('unidad') == 'L' ? 'selected' : '' }}>Litro (L)</option>
                                    <option value="ml" {{ old('unidad') == 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                                    <option value="unidad" {{ old('unidad') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                    <option value="paquete" {{ old('unidad') == 'paquete' ? 'selected' : '' }}>Paquete</option>
                                    <option value="docena" {{ old('unidad') == 'docena' ? 'selected' : '' }}>Docena</option>
                                    <option value="atado" {{ old('unidad') == 'atado' ? 'selected' : '' }}>Atado</option>
                                    <option value="bandeja" {{ old('unidad') == 'bandeja' ? 'selected' : '' }}>Bandeja</option>
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
                                       id="origen" name="origen" value="{{ old('origen') }}" 
                                       required placeholder="Ej: Huaral, Cañete, Local...">
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
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
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
                                        <option value="{{ $vendedor->id }}" {{ old('vendedor_id') == $vendedor->id ? 'selected' : '' }}>
                                            {{ $vendedor->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendedor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Dejar vacío si el producto es del administrador</div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN DE IMAGEN DENTRO DEL FORMULARIO -->
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen del Producto</label>
                        <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                               id="imagen" name="imagen" 
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.bmp,.tiff" 
                               onchange="validarImagen(this)">
                        @error('imagen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Formatos permitidos: JPG, JPEG, PNG, GIF, WEBP, SVG, BMP, TIFF. Tamaño máximo: 2MB.
                            Recomendado: 600x600 px para mejor calidad.
                        </div>
                        
                        <!-- Vista previa de imagen -->
                        <div class="text-center mt-3">
                            <img id="preview" src="#" alt="Vista previa de la imagen" 
                                 class="img-thumbnail mb-2" style="max-width: 100%; max-height: 200px; display: none;">
                            <p class="text-muted small" id="preview-text">
                                La imagen seleccionada aparecerá aquí
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado del Producto</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1"
                                           {{ old('activo', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">Producto activo</label>
                                </div>
                                <div class="form-text">Los productos inactivos no se mostrarán en la tienda</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Aprobación</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="aprobado" name="aprobado" value="1"
                                           {{ old('aprobado', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aprobado">Producto aprobado</label>
                                </div>
                                <div class="form-text">Los productos no aprobados no se mostrarán en la tienda</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="fa fa-undo"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-save"></i> Crear Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Información de Ayuda -->
        <div class="card shadow">
            <div class="card-header bg-light">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-info-circle"></i> Consejos
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <i class="fa fa-check text-success me-2"></i>
                        Usa nombres claros y descriptivos
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-check text-success me-2"></i>
                        Incluye todas las características importantes
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-check text-success me-2"></i>
                        Usa precios competitivos
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-check text-success me-2"></i>
                        Mantén el stock actualizado
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-check text-success me-2"></i>
                        Usa imágenes de alta calidad
                    </li>
                </ul>
            </div>
        </div>

        <!-- Información del Image Service -->
        <div class="card shadow mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-cloud-upload"></i> Servicio de Imágenes
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">
                    Las imágenes se almacenan en nuestro servicio externo especializado:
                </p>
                <ul class="list-unstyled small">
                    <li class="mb-1">
                        <i class="fa fa-check text-success me-2"></i>
                        Almacenamiento seguro
                    </li>
                    <li class="mb-1">
                        <i class="fa fa-check text-success me-2"></i>
                        Redimensionamiento automático
                    </li>
                    <li class="mb-1">
                        <i class="fa fa-check text-success me-2"></i>
                        Optimización de calidad
                    </li>
                    <li class="mb-1">
                        <i class="fa fa-check text-success me-2"></i>
                        Entrega rápida por CDN
                    </li>
                </ul>
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

// Validación de formulario
document.getElementById('form-crear-producto').addEventListener('submit', function(e) {
    const precio = document.getElementById('precio').value;
    const stock = document.getElementById('stock').value;
    
    if (precio < 0) {
        e.preventDefault();
        alert('El precio no puede ser negativo');
        return;
    }
    
    if (stock < 0) {
        e.preventDefault();
        alert('El stock no puede ser negativo');
        return;
    }
});
</script>
@endpush
@endsection