@extends('layouts.admin')

@section('title', 'Detalle del Cliente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detalle del Cliente</h2>
    <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <!-- Información del Cliente -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-user"></i> Información Personal
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fa fa-user fa-2x text-white"></i>
                    </div>
                </div>
                
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nombre:</strong></td>
                        <td>{{ $cliente->nombre }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $cliente->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Teléfono:</strong></td>
                        <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dirección:</strong></td>
                        <td>{{ $cliente->direccion ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Registro:</strong></td>
                        <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Estado:</strong></td>
                        <td>
                            <span class="badge bg-{{ $cliente->activo ? 'success' : 'secondary' }}">
                                {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-chart-bar"></i> Estadísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-success mb-1">{{ $estadisticas['total_pedidos'] }}</h4>
                            <small class="text-muted">Total Pedidos</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-primary mb-1">{{ $estadisticas['pedidos_entregados'] }}</h4>
                            <small class="text-muted">Entregados</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <h4 class="text-warning mb-1">{{ $estadisticas['pedidos_pendientes'] }}</h4>
                            <small class="text-muted">Pendientes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <h4 class="text-danger mb-1">S/ {{ number_format($estadisticas['total_gastado'], 0) }}</h4>
                            <small class="text-muted">Total Gastado</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos Recientes y Acciones -->
    <div class="col-md-8">
        <!-- Pedidos Recientes -->
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-history"></i> Pedidos Recientes
                </h6>
                <a href="{{ route('admin.clientes.pedidos', $cliente->id) }}" class="btn btn-sm btn-success">
                    Ver Todos
                </a>
            </div>
            <div class="card-body">
                @if($cliente->pedidos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Pedido #</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->pedidos->take(5) as $pedido)
                                <tr>
                                    <td><strong>#{{ $pedido->id }}</strong></td>
                                    <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                    <td>S/ {{ number_format($pedido->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $pedido->estado == 'pendiente' ? 'warning' : ($pedido->estado == 'entregado' ? 'success' : ($pedido->estado == 'cancelado' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($pedido->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pedidos.show', $pedido->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Este cliente aún no ha realizado pedidos.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-cogs"></i> Acciones
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-warning me-2">
                        <i class="fa fa-edit"></i> Editar Cliente
                    </a>
                    <form method="POST" action="{{ route('admin.clientes.toggle-activo', $cliente->id) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-{{ $cliente->activo ? 'secondary' : 'success' }}">
                            <i class="fa fa-{{ $cliente->activo ? 'ban' : 'check' }}"></i> 
                            {{ $cliente->activo ? 'Desactivar' : 'Activar' }} Cuenta
                        </button>
                    </form>
                    <a href="mailto:{{ $cliente->email }}" class="btn btn-outline-info ms-2">
                        <i class="fa fa-envelope"></i> Contactar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection