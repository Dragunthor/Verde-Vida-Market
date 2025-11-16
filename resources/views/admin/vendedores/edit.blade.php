@extends('layouts.admin')

@section('title', 'Editar Vendedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Editar Vendedor: {{ $vendedor->usuario->nombre }}</h2>
    <a href="{{ route('admin.vendedores.show', $vendedor->id) }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-edit"></i> Editar Información
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.vendedores.update', $vendedor->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $vendedor->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                  id="direccion" name="direccion" rows="2">{{ old('direccion', $vendedor->direccion) }}</textarea>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="metodos_entrega" class="form-label">Métodos de Entrega</label>
                                <select class="form-select @error('metodos_entrega') is-invalid @enderror" 
                                        id="metodos_entrega" name="metodos_entrega" required>
                                    <option value="recogida" {{ old('metodos_entrega', $vendedor->metodos_entrega) == 'recogida' ? 'selected' : '' }}>Recogida</option>
                                    <option value="delivery" {{ old('metodos_entrega', $vendedor->metodos_entrega) == 'delivery' ? 'selected' : '' }}>Delivery</option>
                                    <option value="ambos" {{ old('metodos_entrega', $vendedor->metodos_entrega) == 'ambos' ? 'selected' : '' }}>Ambos</option>
                                </select>
                                @error('metodos_entrega')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="horario_atencion" class="form-label">Horario de Atención</label>
                                <input type="text" class="form-control @error('horario_atencion') is-invalid @enderror" 
                                       id="horario_atencion" name="horario_atencion" 
                                       value="{{ old('horario_atencion', $vendedor->horario_atencion) }}">
                                @error('horario_atencion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="activo_vendedor" name="activo_vendedor" 
                                   {{ old('activo_vendedor', $vendedor->activo_vendedor) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo_vendedor">
                                Vendedor activo
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.vendedores.show', $vendedor->id) }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Actualizar Vendedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Información Adicional -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-info-circle"></i> Información del Vendedor
                </h6>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> #{{ $vendedor->id }}</p>
                <p><strong>Nombre:</strong> {{ $vendedor->usuario->nombre }}</p>
                <p><strong>Email:</strong> {{ $vendedor->usuario->email }}</p>
                <p><strong>Teléfono:</strong> {{ $vendedor->usuario->telefono ?? 'N/A' }}</p>
                <p><strong>Fecha de Registro:</strong> {{ $vendedor->created_at->format('d/m/Y H:i') }}</p>
                <hr>
                <p class="small text-muted">
                    <i class="fa fa-exclamation-triangle"></i> 
                    Los cambios se aplicarán inmediatamente.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection