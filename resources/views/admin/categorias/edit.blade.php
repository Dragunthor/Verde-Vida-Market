@extends('layouts.admin')

@section('title', 'Editar Categoría')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Editar Categoría</h2>
    <a href="{{ route('admin.categorias') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Categoría *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="{{ $categoria['nombre'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required>{{ $categoria['descripcion'] }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen de la Categoría</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        <div class="form-text">Formatos: JPG, PNG, GIF. Máx 2MB</div>
                    </div>

                    <div class="text-center">
                        <img id="preview" src="{{ asset('images/' . $categoria['imagen']) }}" 
                             alt="Vista previa" class="img-thumbnail mt-3" style="max-width: 200px;">
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fa fa-save"></i> Actualizar Categoría
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('imagen').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection