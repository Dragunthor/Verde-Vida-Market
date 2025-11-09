@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Editar Producto</h2>
    <a href="{{ route('admin.productos') }}" class="btn btn-outline-secondary">
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
                        <label for="nombre" class="form-label">Nombre del Producto *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="{{ $producto['nombre'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción *</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required>{{ $producto['descripcion'] }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio (S/) *</label>
                                <input type="number" class="form-control" id="precio" name="precio" 
                                       value="{{ $producto['precio'] }}" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock *</label>
                                <input type="number" class="form-control" id="stock" name="stock" 
                                       value="{{ $producto['stock'] }}" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unidad" class="form-label">Unidad de Medida *</label>
                                <select class="form-select" id="unidad" name="unidad" required>
                                    <option value="">Seleccionar unidad</option>
                                    <option value="Kg" {{ $producto['unidad'] == 'Kg' ? 'selected' : '' }}>Kilogramo (Kg)</option>
                                    <option value="g" {{ $producto['unidad'] == 'g' ? 'selected' : '' }}>Gramo (g)</option>
                                    <option value="L" {{ $producto['unidad'] == 'L' ? 'selected' : '' }}>Litro (L)</option>
                                    <option value="ml" {{ $producto['unidad'] == 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                                    <option value="Unidad" {{ $producto['unidad'] == 'Unidad' ? 'selected' : '' }}>Unidad</option>
                                    <option value="Paquete" {{ $producto['unidad'] == 'Paquete' ? 'selected' : '' }}>Paquete</option>
                                    <option value="Docena" {{ $producto['unidad'] == 'Docena' ? 'selected' : '' }}>Docena</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="origen" class="form-label">Origen *</label>
                                <select class="form-select" id="origen" name="origen" required>
                                    <option value="">Seleccionar origen</option>
                                    <option value="Local" {{ $producto['origen'] == 'Local' ? 'selected' : '' }}>Local</option>
                                    <option value="Nacional" {{ $producto['origen'] == 'Nacional' ? 'selected' : '' }}>Nacional</option>
                                    <option value="Importado" {{ $producto['origen'] == 'Importado' ? 'selected' : '' }}>Importado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoria_id" class="form-label">Categoría *</label>
                                <select class="form-select" id="categoria_id" name="categoria_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria['id'] }}" 
                                            {{ $producto['categoria_id'] == $categoria['id'] ? 'selected' : '' }}>
                                            {{ $categoria['nombre'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                                           {{ $producto['activo'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">Producto activo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen del Producto</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        <div class="form-text">Formatos: JPG, PNG, GIF. Máx 2MB</div>
                    </div>

                    <div class="text-center">
                        <img id="preview" src="{{ asset('images/' . $producto['imagen']) }}" 
                             alt="Vista previa" class="img-thumbnail mt-3" style="max-width: 200px;">
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fa fa-save"></i> Actualizar Producto
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