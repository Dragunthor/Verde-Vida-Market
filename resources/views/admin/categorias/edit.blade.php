@extends('layouts.admin')

@section('title', 'Editar Categoría')
@section('page-title', 'Editar Categoría: ' . $categoria->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Editar Categoría: <span class="text-success">{{ $categoria->nombre }}</span></h2>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver a Categorías
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-edit"></i> Editar Información de la Categoría
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categorias.update', $categoria->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $categoria->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen de la Categoría</label>
                        <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                               id="imagen" name="imagen" accept="image/*">
                        @error('imagen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Deja en blanco para mantener la imagen actual. Formatos: JPG, PNG, GIF, WEBP. Máx 2MB.
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fa fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-save"></i> Actualizar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Vista Previa Actual -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-image"></i> Imagen Actual
                </h6>
            </div>
            <div class="card-body text-center">
                <img src="{{ $categoria->imagen ? asset('storage/' . $categoria->imagen) : asset('images/placeholder-category.jpg') }}" 
                     alt="{{ $categoria->nombre }}" 
                     class="img-thumbnail mb-3" style="max-width: 100%;">
                <p class="text-muted small mb-0">
                    {{ $categoria->imagen ? 'Imagen actual de la categoría' : 'Sin imagen asignada' }}
                </p>
            </div>
        </div>

        <!-- Información de la Categoría -->
        <div class="card shadow mt-4">
            <div class="card-header bg-light">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-info-circle"></i> Información de la Categoría
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Productos asociados:</strong><br>
                    <span class="badge bg-primary">{{ $categoria->productos_count }} productos</span>
                </div>
                <div class="mb-3">
                    <strong>Fecha de creación:</strong><br>
                    {{ $categoria->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Última actualización:</strong><br>
                    {{ $categoria->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <!-- Vista Previa Nueva Imagen -->
        <div class="card shadow mt-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-eye"></i> Vista Previa Nueva Imagen
                </h6>
            </div>
            <div class="card-body text-center">
                <img id="preview" src="#" alt="Vista previa de la nueva imagen" 
                     class="img-thumbnail mb-3" style="max-width: 100%; display: none;">
                <p class="text-muted small" id="preview-text">
                    La nueva imagen seleccionada aparecerá aquí
                </p>
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