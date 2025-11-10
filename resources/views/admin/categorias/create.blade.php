@extends('layouts.admin')

@section('title', 'Crear Categoría')
@section('page-title', 'Crear Nueva Categoría')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Crear Nueva Categoría</h2>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver a Categorías
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-plus-circle"></i> Información de la Categoría
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categorias.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre') }}" required 
                               placeholder="Ej: Frutas Orgánicas, Lácteos Naturales...">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="4" 
                                  placeholder="Describe los productos que pertenecerán a esta categoría..."
                                  required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Esta descripción ayudará a los usuarios a entender el tipo de productos de esta categoría.</div>
                    </div>

                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen de la Categoría</label>
                        <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                               id="imagen" name="imagen" accept="image/*">
                        @error('imagen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Formatos aceptados: JPG, PNG, GIF, WEBP. Tamaño máximo: 2MB. 
                            Recomendado: 400x400 px para mejor calidad.
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="fa fa-undo"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-save"></i> Crear Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Vista Previa de Imagen -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-image"></i> Vista Previa
                </h6>
            </div>
            <div class="card-body text-center">
                <img id="preview" src="#" alt="Vista previa de la imagen" 
                     class="img-thumbnail mb-3" style="max-width: 100%; display: none;">
                <p class="text-muted small" id="preview-text">
                    La imagen seleccionada aparecerá aquí
                </p>
            </div>
        </div>

        <!-- Información de Ayuda -->
        <div class="card shadow mt-4">
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
                        La descripción debe ser útil para los usuarios
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-check text-success me-2"></i>
                        Las imágenes deben ser de alta calidad
                    </li>
                    <li class="mb-2">
                        <i class="fa fa-check text-success me-2"></i>
                        Organiza las categorías de forma lógica
                    </li>
                </ul>
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

    // Validación de formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        
        if (!nombre) {
            e.preventDefault();
            alert('El nombre de la categoría es obligatorio');
            return;
        }
        
        if (!descripcion) {
            e.preventDefault();
            alert('La descripción de la categoría es obligatoria');
            return;
        }
    });
</script>
@endpush
@endsection