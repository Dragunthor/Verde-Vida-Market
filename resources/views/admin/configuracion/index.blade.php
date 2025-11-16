@extends('layouts.admin')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Configuración del Sistema</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-3">
        <!-- Navegación de Configuración -->
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-cogs"></i> Secciones
                </h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="fa fa-globe fa-fw"></i> General
                </a>
                <a href="#negocio" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fa fa-store fa-fw"></i> Negocio
                </a>
                <a href="#vendedores" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fa fa-users fa-fw"></i> Vendedores
                </a>
                <a href="#pedidos" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fa fa-shopping-cart fa-fw"></i> Pedidos
                </a>
                <a href="#pagos" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fa fa-credit-card fa-fw"></i> Pagos
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="tab-content">
            <!-- Configuración General -->
            <div class="tab-pane fade show active" id="general">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-globe"></i> Configuración General
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="#">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre_tienda" class="form-label">Nombre de la Tienda</label>
                                        <input type="text" class="form-control" id="nombre_tienda" 
                                               value="VerdeVida Market" readonly>
                                        <small class="form-text text-muted">Nombre público de tu ecommerce</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email_contacto" class="form-label">Email de Contacto</label>
                                        <input type="email" class="form-control" id="email_contacto" 
                                               value="contacto@verdevida.com">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono_contacto" class="form-label">Teléfono de Contacto</label>
                                        <input type="text" class="form-control" id="telefono_contacto" 
                                               value="+51 123 456 789">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="direccion_tienda" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion_tienda" 
                                               value="Av. Principal 123, Lima">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion_tienda" class="form-label">Descripción de la Tienda</label>
                                <textarea class="form-control" id="descripcion_tienda" rows="3">
Tu mercado de confianza para productos frescos, naturales y orgánicos. Conectamos productores locales con consumidores conscientes.
                                </textarea>
                            </div>

                            <button type="submit" class="btn btn-success" disabled>
                                <i class="fa fa-save"></i> Guardar Cambios (Próximamente)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Configuración de Negocio -->
            <div class="tab-pane fade" id="negocio">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-store"></i> Configuración de Negocio
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="#">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="moneda" class="form-label">Moneda</label>
                                        <select class="form-select" id="moneda">
                                            <option value="PEN" selected>Soles (S/)</option>
                                            <option value="USD">Dólares ($)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="impuesto" class="form-label">IGV (%)</label>
                                        <input type="number" class="form-control" id="impuesto" 
                                               value="18" min="0" max="100" step="0.1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="comision_vendedores" class="form-label">Comisión Vendedores (%)</label>
                                        <input type="number" class="form-control" id="comision_vendedores" 
                                               value="10" min="0" max="50" step="0.1">
                                        <small class="form-text text-muted">Porcentaje que se queda la plataforma</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Horario de Atención</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="time" class="form-control" value="08:00">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="time" class="form-control" value="22:00">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" disabled>
                                <i class="fa fa-save"></i> Guardar Cambios (Próximamente)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Configuración de Vendedores -->
            <div class="tab-pane fade" id="vendedores">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-users"></i> Configuración de Vendedores
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="#">
                            @csrf
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="vendedores_automaticos" checked>
                                    <label class="form-check-label" for="vendedores_automaticos">
                                        Aprobación automática de vendedores
                                    </label>
                                </div>
                                <small class="form-text text-muted">Si está desactivado, requieren aprobación manual</small>
                            </div>

                            <div class="mb-3">
                                <label for="limite_productos" class="form-label">Límite de Productos por Vendedor</label>
                                <input type="number" class="form-control" id="limite_productos" value="50" min="1">
                                <small class="form-text text-muted">Número máximo de productos que puede publicar un vendedor</small>
                            </div>

                            <div class="mb-3">
                                <label for="comision_categoria" class="form-label">Comisiones por Categoría</label>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Categoría</th>
                                                <th>Comisión (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Frutas y Verduras</td>
                                                <td><input type="number" class="form-control form-control-sm" value="8"></td>
                                            </tr>
                                            <tr>
                                                <td>Productos Orgánicos</td>
                                                <td><input type="number" class="form-control form-control-sm" value="10"></td>
                                            </tr>
                                            <tr>
                                                <td>Productos Lácteos</td>
                                                <td><input type="number" class="form-control form-control-sm" value="12"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" disabled>
                                <i class="fa fa-save"></i> Guardar Cambios (Próximamente)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Configuración de Pedidos -->
            <div class="tab-pane fade" id="pedidos">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-shopping-cart"></i> Configuración de Pedidos
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="#">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tiempo_preparacion" class="form-label">Tiempo de Preparación (horas)</label>
                                        <input type="number" class="form-control" id="tiempo_preparacion" value="2" min="1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pedido_minimo" class="form-label">Pedido Mínimo (S/)</label>
                                        <input type="number" class="form-control" id="pedido_minimo" value="20" min="0" step="0.1">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Métodos de Entrega Disponibles</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="delivery" checked>
                                    <label class="form-check-label" for="delivery">Delivery a domicilio</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="recogida" checked>
                                    <label class="form-check-label" for="recogida">Recogida en tienda</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="costo_delivery" class="form-label">Costo de Delivery (S/)</label>
                                <input type="number" class="form-control" id="costo_delivery" value="5" min="0" step="0.1">
                            </div>

                            <button type="submit" class="btn btn-success" disabled>
                                <i class="fa fa-save"></i> Guardar Cambios (Próximamente)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Configuración de Pagos -->
            <div class="tab-pane fade" id="pagos">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-credit-card"></i> Configuración de Pagos
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="#">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Métodos de Pago Disponibles</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="efectivo" checked>
                                    <label class="form-check-label" for="efectivo">Efectivo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="transferencia" checked>
                                    <label class="form-check-label" for="transferencia">Transferencia Bancaria</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="tarjeta">
                                    <label class="form-check-label" for="tarjeta">Tarjeta de Crédito/Débito</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="dias_pago_vendedores" class="form-label">Días para Pago a Vendedores</label>
                                <input type="number" class="form-control" id="dias_pago_vendedores" value="7" min="1">
                                <small class="form-text text-muted">Días después de la entrega para pagar a vendedores</small>
                            </div>

                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                <strong>Información:</strong> La configuración de pasarelas de pago requiere integración con servicios externos.
                            </div>

                            <button type="submit" class="btn btn-success" disabled>
                                <i class="fa fa-save"></i> Guardar Cambios (Próximamente)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item.active {
    background-color: #198754;
    border-color: #198754;
}
</style>

<script>
// Activar la pestaña correcta al hacer clic en los enlaces de la sidebar
document.addEventListener('DOMContentLoaded', function() {
    var triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="list"]'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
});
</script>
@endsection