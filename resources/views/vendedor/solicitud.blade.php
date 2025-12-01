@extends('layouts.app')

@section('title', 'Ser Vendedor - VerdeVida Market')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="fa fa-store"></i> Convertirse en Vendedor
                </h4>
            </div>
            <div class="card-body">
                @if(auth()->user()->esVendedor())
                    @php
                        $perfilVendedor = auth()->user()->perfilVendedor;
                        $esVendedorActivo = $perfilVendedor && $perfilVendedor->activo_vendedor;
                    @endphp
                    
                    @if($esVendedorActivo)
                        <div class="alert alert-success">
                            <h5><i class="fa fa-check-circle"></i> ¡Ya eres vendedor!</h5>
                            <p class="mb-3">Tu cuenta de vendedor ha sido aprobada. Puedes comenzar a agregar productos.</p>
                            <a href="{{ route('vendedor.dashboard') }}" class="btn btn-success">
                                <i class="fa fa-tachometer"></i> Ir al Dashboard de Vendedor
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <h5><i class="fa fa-clock-o"></i> Solicitud en Revisión</h5>
                            <p class="mb-0">Tu solicitud para ser vendedor está siendo revisada por nuestro equipo. 
                            Te notificaremos por email una vez sea aprobada.</p>
                        </div>
                        
                        <!-- Mostrar información de la solicitud -->
                        @if($perfilVendedor)
                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Tu Solicitud</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Descripción:</strong> {{ $perfilVendedor->descripcion }}</p>
                                <p><strong>Dirección:</strong> {{ $perfilVendedor->direccion }}</p>
                                <p><strong>Métodos de entrega:</strong> {{ ucfirst($perfilVendedor->metodos_entrega) }}</p>
                                <p><strong>Horario de atención:</strong> {{ $perfilVendedor->horario_atencion }}</p>
                                <p><strong>Fecha de solicitud:</strong> {{ $perfilVendedor->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endif
                    @endif
                
                @else
                    <div class="alert alert-info">
                        <h5><i class="fa fa-info-circle"></i> Información Importante</h5>
                        <p class="mb-2">Al convertirte en vendedor podrás:</p>
                        <ul class="mb-3">
                            <li>Publicar y vender tus productos orgánicos</li>
                            <li>Llegar a más clientes interesados en productos naturales</li>
                            <li>Gestionar tus pedidos y ventas desde un panel especial</li>
                            <li>Recibir pagos seguros a través de nuestra plataforma</li>
                        </ul>
                        <p class="mb-0"><strong>Comisión:</strong> 10% por cada venta realizada</p>
                    </div>

                    <form method="POST" action="{{ route('vendedor.enviarSolicitud') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción de tu negocio <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="4" 
                                      placeholder="Describe los productos que ofreces, tu experiencia, filosofía de cultivo/producción, etc." 
                                      required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Esta información será visible para los clientes.</div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección de operación <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                      id="direccion" name="direccion" rows="2" 
                                      placeholder="Dirección desde donde operarás (no se mostrará públicamente)" 
                                      required>{{ old('direccion') }}</textarea>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="metodos_entrega" class="form-label">Métodos de entrega <span class="text-danger">*</span></label>
                                    <select class="form-select @error('metodos_entrega') is-invalid @enderror" 
                                            id="metodos_entrega" name="metodos_entrega" required>
                                        <option value="">Seleccionar método</option>
                                        <option value="recogida" {{ old('metodos_entrega') == 'recogida' ? 'selected' : '' }}>Solo recogida en local</option>
                                        <option value="delivery" {{ old('metodos_entrega') == 'delivery' ? 'selected' : '' }}>Solo delivery</option>
                                        <option value="ambos" {{ old('metodos_entrega') == 'ambos' ? 'selected' : '' }}>Ambos (recogida y delivery)</option>
                                    </select>
                                    @error('metodos_entrega')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="horario_atencion" class="form-label">Horario de atención</label>
                                    <input type="text" class="form-control @error('horario_atencion') is-invalid @enderror" 
                                           id="horario_atencion" name="horario_atencion" 
                                           placeholder="Ej: Lunes a Viernes 8:00 AM - 6:00 PM" 
                                           value="{{ old('horario_atencion') }}">
                                    @error('horario_atencion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terminos') is-invalid @enderror" 
                                   type="checkbox" id="terminos" name="terminos" required>
                            <label class="form-check-label" for="terminos">
                                Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#terminosModal">Términos y Condiciones para Vendedores</a> 
                                y comprendo que se aplicará una comisión del 10% sobre cada venta.
                            </label>
                            @error('terminos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fa fa-paper-plane"></i> Enviar Solicitud
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Beneficios de ser vendedor -->
        @if(!auth()->user()->esVendedor())
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fa fa-star"></i> Beneficios de ser Vendedor</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fa fa-users text-success fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Más Clientes</h6>
                                <p class="text-muted mb-0">Llega a miles de clientes interesados en productos orgánicos</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fa fa-shield text-success fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Pagos Seguros</h6>
                                <p class="text-muted mb-0">Sistema de pagos protegido y confiable</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fa fa-line-chart text-success fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Herramientas de Gestión</h6>
                                <p class="text-muted mb-0">Dashboard completo para administrar tus productos y ventas</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fa fa-headphones text-success fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6>Soporte Dedicado</h6>
                                <p class="text-muted mb-0">Equipo de soporte especializado para vendedores</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de Términos y Condiciones -->
<div class="modal fade" id="terminosModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Términos y Condiciones para Vendedores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>1. Aceptación de los Términos</h6>
                <p>Al registrarse como vendedor en VerdeVida Market, usted acepta cumplir con estos términos y condiciones, así como con nuestras políticas de privacidad y uso de la plataforma.</p>
                
                <h6>2. Responsabilidades del Vendedor</h6>
                <ul>
                    <li>Garantizar la calidad y autenticidad de todos los productos listados</li>
                    <li>Mantener información precisa y actualizada sobre productos, precios y disponibilidad</li>
                    <li>Cumplir con los plazos de entrega establecidos</li>
                    <li>Proporcionar un servicio al cliente profesional y responder consultas oportunamente</li>
                </ul>
                
                <h6>3. Comisiones y Pagos</h6>
                <p>VerdeVida Market aplica una comisión del 10% sobre cada venta realizada a través de la plataforma. Los pagos a vendedores se procesan mensualmente, dentro de los primeros 5 días hábiles del mes siguiente.</p>
                
                <h6>4. Política de Productos</h6>
                <p>Está prohibido listar productos que:</p>
                <ul>
                    <li>No sean orgánicos, naturales o ecológicos</li>
                    <li>Incumplan las normativas sanitarias locales</li>
                    <li>Estén caducados o en mal estado</li>
                    <li>Infrinjan derechos de propiedad intelectual</li>
                </ul>
                
                <h6>5. Cancelaciones y Reembolsos</h6>
                <p>Los vendedores deben aceptar nuestra política de cancelaciones y reembolsos, que permite a los clientes solicitar reembolsos dentro de los 7 días posteriores a la recepción del producto en caso de problemas de calidad o entrega.</p>
                
                <h6>6. Suspensión de Cuenta</h6>
                <p>VerdeVida Market se reserva el derecho de suspender o cancelar cuentas de vendedores que:</p>
                <ul>
                    <li>Incumplan repetidamente estos términos</li>
                    <li>Reciban múltiples quejas de clientes</li>
                    <li>Listen productos prohibidos</li>
                    <li>Realicen actividades fraudulentas</li>
                </ul>
                
                <h6>7. Modificaciones</h6>
                <p>Nos reservamos el derecho de modificar estos términos en cualquier momento. Los cambios serán notificados por email con 15 días de anticipación.</p>
                
                <div class="alert alert-warning mt-3">
                    <i class="fa fa-exclamation-triangle"></i> <strong>Importante:</strong> Al aceptar estos términos, usted reconoce haber leído y comprendido todas las condiciones establecidas.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>
@endsection