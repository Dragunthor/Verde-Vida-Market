@extends('layouts.vendedor')

@section('title', 'Mi Perfil')
@section('page-title', 'Mi Perfil')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-user"></i> Datos Personales
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('perfil.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre', auth()->user()->nombre) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" value="{{ old('telefono', auth()->user()->telefono) }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                          id="direccion" name="direccion" rows="2">{{ old('direccion', auth()->user()->direccion) }}</textarea>
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Actualizar Datos
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información de Vendedor -->
        <div class="card shadow mt-4">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-store"></i> Información de Mi Tienda
                </h6>
            </div>
            <div class="card-body">
                @php
                    $perfilVendedor = auth()->user()->perfilVendedor;
                @endphp
                
                @if($perfilVendedor)
                    <form method="POST" action="{{ route('perfil.update-vendedor') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción de tu Tienda <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                            id="descripcion" name="descripcion" rows="4" 
                                            placeholder="Describe los productos que ofreces, tu filosofía de cultivo/producción, etc."
                                            required>{{ old('descripcion', $perfilVendedor->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Mínimo 50 caracteres. Esta información será visible para los clientes.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="direccion_operacion" class="form-label">Dirección de Operación <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('direccion_operacion') is-invalid @enderror" 
                                            id="direccion_operacion" name="direccion_operacion" rows="2" 
                                            placeholder="Dirección desde donde operas"
                                            required>{{ old('direccion_operacion', $perfilVendedor->direccion) }}</textarea>
                                    @error('direccion_operacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="metodos_entrega" class="form-label">Métodos de Entrega <span class="text-danger">*</span></label>
                                    <select class="form-select @error('metodos_entrega') is-invalid @enderror" 
                                            id="metodos_entrega" name="metodos_entrega" required>
                                        <option value="">Seleccionar método</option>
                                        <option value="recogida" {{ old('metodos_entrega', $perfilVendedor->metodos_entrega) == 'recogida' ? 'selected' : '' }}>Solo recogida en local</option>
                                        <option value="delivery" {{ old('metodos_entrega', $perfilVendedor->metodos_entrega) == 'delivery' ? 'selected' : '' }}>Solo delivery</option>
                                        <option value="ambos" {{ old('metodos_entrega', $perfilVendedor->metodos_entrega) == 'ambos' ? 'selected' : '' }}>Ambos (recogida y delivery)</option>
                                    </select>
                                    @error('metodos_entrega')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="horario_atencion" class="form-label">Horario de Atención</label>
                                    <input type="text" class="form-control @error('horario_atencion') is-invalid @enderror" 
                                        id="horario_atencion" name="horario_atencion" 
                                        placeholder="Ej: Lunes a Viernes 8:00 AM - 6:00 PM"
                                        value="{{ old('horario_atencion', $perfilVendedor->horario_atencion) }}">
                                    @error('horario_atencion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Estado de la Cuenta</label>
                                    <div class="p-2 border rounded bg-light">
                                        <strong>Estado:</strong> 
                                        <span class="badge bg-{{ $perfilVendedor->activo_vendedor ? 'success' : 'warning' }}">
                                            {{ $perfilVendedor->activo_vendedor ? 'Activo' : 'Pendiente' }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            @if($perfilVendedor->activo_vendedor)
                                                Tu cuenta de vendedor está activa
                                            @else
                                                En revisión por el administrador
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Actualizar Información de Tienda
                            </button>
                        </div>
                    </form>

                    <!-- Opción para dejar de ser vendedor -->
                    <div class="alert alert-warning mt-4">
                        <h6><i class="fa fa-exclamation-triangle"></i> Dejar de ser Vendedor</h6>
                        <p class="mb-2">Si deseas dejar de ser vendedor, puedes solicitar la desactivación de tu cuenta.</p>
                        <form method="POST" action="{{ route('perfil.dejar-vendedor') }}" onsubmit="return confirm('¿Estás seguro de que quieres dejar de ser vendedor? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="fa fa-times"></i> Solicitar dejar de ser Vendedor
                            </button>
                        </form>
                    </div>

                @else
                    <div class="text-center py-3">
                        <i class="fa fa-store fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No tienes perfil de vendedor activo.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Estadísticas Rápidas -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-chart-bar"></i> Mis Estadísticas
                </h6>
            </div>
            <div class="card-body">
                @php
                    $perfilVendedor = auth()->user()->perfilVendedor;
                @endphp
                <div class="text-center mb-3">
                    <div class="mb-3">
                        <h4>{{ auth()->user()->productos()->count() }}</h4>
                        <small class="text-muted">Productos</small>
                    </div>
                    <div class="mb-3">
                        <h4>{{ $perfilVendedor ? $perfilVendedor->total_ventas : 0 }}</h4>
                        <small class="text-muted">Ventas Totales</small>
                    </div>
                    <div class="mb-3">
                        <h4>
                            @if($perfilVendedor)
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $perfilVendedor->calificacion_promedio)
                                        <i class="fa fa-star text-warning"></i>
                                    @else
                                        <i class="fa fa-star-o text-muted"></i>
                                    @endif
                                @endfor
                            @else
                                <span class="text-muted">Sin calificación</span>
                            @endif
                        </h4>
                        <small class="text-muted">Calificación</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cambio de Contraseña -->
        <div class="card shadow mt-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-key"></i> Cambiar Contraseña
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('perfil.cambiar-password') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" name="new_password" required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" 
                               id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fa fa-key"></i> Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Eliminar Cuenta -->
        <div class="card shadow mt-4">
            <div class="card-header bg-danger text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-exclamation-triangle"></i> Eliminar Cuenta
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Eliminar tu cuenta es permanente y no se puede deshacer.</p>
                <button type="button" class="btn btn-outline-danger btn-sm w-100" 
                        data-bs-toggle="modal" data-bs-target="#eliminarCuentaModal">
                    <i class="fa fa-trash"></i> Eliminar Mi Cuenta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Eliminación de Cuenta (mismo que en la otra vista) -->
<div class="modal fade" id="eliminarCuentaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación de Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h6><i class="fa fa-exclamation-triangle"></i> ¡Advertencia!</h6>
                    <p class="mb-0">Esta acción no se puede deshacer. Se eliminarán todos tus datos, pedidos, y si eres vendedor, todos tus productos y ventas.</p>
                </div>
                <p>¿Estás absolutamente seguro de que quieres eliminar tu cuenta?</p>
                <form method="POST" action="{{ route('perfil.destroy') }}" id="eliminarCuentaForm">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label for="confirmacion" class="form-label">
                            Escribe "ELIMINAR" para confirmar:
                        </label>
                        <input type="text" class="form-control" id="confirmacion" name="confirmacion" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarEliminacion()">Eliminar Cuenta</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmarEliminacion() {
        const confirmacion = document.getElementById('confirmacion').value;
        if (confirmacion === 'ELIMINAR') {
            document.getElementById('eliminarCuentaForm').submit();
        } else {
            alert('Por favor, escribe "ELIMINAR" para confirmar la eliminación.');
        }
    }
</script>
@endpush
@endsection