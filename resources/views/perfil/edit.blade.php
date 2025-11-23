@extends('layouts.app')

@section('title', 'Mi Perfil - VerdeVida Market')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="fa fa-user"></i> Mi Perfil
                </h4>
            </div>
            <div class="card-body">
                <!-- Pestañas -->
                <ul class="nav nav-tabs mb-4" id="perfilTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="datos-tab" data-bs-toggle="tab" data-bs-target="#datos" type="button" role="tab">
                            <i class="fa fa-info-circle"></i> Datos Personales
                        </button>
                    </li>
                    @if(auth()->user()->esVendedor())
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="vendedor-tab" data-bs-toggle="tab" data-bs-target="#vendedor" type="button" role="tab">
                            <i class="fa fa-store"></i> Información de Vendedor
                        </button>
                    </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seguridad-tab" data-bs-toggle="tab" data-bs-target="#seguridad" type="button" role="tab">
                            <i class="fa fa-shield"></i> Seguridad
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="perfilTabsContent">
                    <!-- Pestaña 1: Datos Personales -->
                    <div class="tab-pane fade show active" id="datos" role="tabpanel">
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

                    <!-- Pestaña 2: Información de Vendedor -->
                    @if(auth()->user()->esVendedor())
                    <div class="tab-pane fade" id="vendedor" role="tabpanel">
                        @php
                            $perfilVendedor = auth()->user()->perfilVendedor;
                        @endphp
                        
                        @if($perfilVendedor)
                            <!-- Formulario de Edición de Información de Tienda -->
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
                                                    placeholder="Dirección desde donde operas (no se mostrará públicamente)"
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
                                                    {{ $perfilVendedor->activo_vendedor ? 'Activo' : 'Pendiente de Aprobación' }}
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    @if($perfilVendedor->activo_vendedor)
                                                        Tu cuenta de vendedor está activa y tus productos son visibles.
                                                    @else
                                                        Tu cuenta está en revisión. Te notificaremos cuando sea aprobada.
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mb-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Actualizar Información de Tienda
                                    </button>
                                </div>
                            </form>

                            <!-- Estadísticas del Vendedor -->
                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Mis Estadísticas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <h5>{{ auth()->user()->productos()->count() }}</h5>
                                            <small class="text-muted">Productos</small>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>{{ $perfilVendedor->total_ventas }}</h5>
                                            <small class="text-muted">Ventas Totales</small>
                                        </div>
                                        <div class="col-md-4">
                                            <h5>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $perfilVendedor->calificacion_promedio)
                                                        <i class="fa fa-star text-warning"></i>
                                                    @else
                                                        <i class="fa fa-star-o text-muted"></i>
                                                    @endif
                                                @endfor
                                            </h5>
                                            <small class="text-muted">Calificación</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Opción para dejar de ser vendedor -->
                            <div class="alert alert-warning mt-4">
                                <h6><i class="fa fa-exclamation-triangle"></i> Dejar de ser Vendedor</h6>
                                <p class="mb-3">Si deseas dejar de ser vendedor, puedes solicitar la desactivación de tu cuenta de vendedor.</p>
                                <form method="POST" action="{{ route('perfil.dejar-vendedor') }}" onsubmit="return confirm('¿Estás seguro de que quieres dejar de ser vendedor? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-warning btn-sm">
                                        <i class="fa fa-times"></i> Solicitar dejar de ser Vendedor
                                    </button>
                                </form>
                            </div>

                        @else
                            <div class="text-center py-4">
                                <i class="fa fa-store fa-3x text-muted mb-3"></i>
                                <h5>No tienes perfil de vendedor</h5>
                                <p class="text-muted">Puedes solicitar convertirte en vendedor para empezar a vender tus productos.</p>
                                <a href="{{ route('vendedor.solicitud') }}" class="btn btn-success">
                                    <i class="fa fa-user-plus"></i> Ser Vendedor
                                </a>
                            </div>
                        @endif
                    </div>
                    @endif

                    <!-- Pestaña 3: Seguridad -->
                    <div class="tab-pane fade" id="seguridad" role="tabpanel">
                        <!-- Cambio de Contraseña -->
                        <div class="mb-4">
                            <h6>Cambiar Contraseña</h6>
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
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-key"></i> Cambiar Contraseña
                                    </button>
                                </div>
                            </form>
                        </div>

                        <hr>

                        <!-- Eliminar Cuenta -->
                        <div class="alert alert-danger">
                            <h6><i class="fa fa-exclamation-triangle"></i> Zona de Peligro</h6>
                            <p class="mb-3">Una vez que elimines tu cuenta, no hay vuelta atrás. Por favor, ten cuidado.</p>
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    data-bs-toggle="modal" data-bs-target="#eliminarCuentaModal">
                                <i class="fa fa-trash"></i> Eliminar Mi Cuenta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Eliminación de Cuenta -->
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

    // Mostrar errores de validación en las pestañas correspondientes
    @if($errors->has('current_password') || $errors->has('new_password'))
        document.getElementById('seguridad-tab').click();
    @endif

    @if($errors->has('nombre') || $errors->has('email') || $errors->has('telefono') || $errors->has('direccion'))
        document.getElementById('datos-tab').click();
    @endif
</script>
@endpush
@endsection