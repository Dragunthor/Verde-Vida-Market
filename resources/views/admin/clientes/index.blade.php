@extends('layouts.admin')

@section('title', 'Gestión de Clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Clientes</h2>
    <div class="btn-group">
        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#filtrosModal">
            <i class="fa fa-filter"></i> Filtros
        </button>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-users"></i> Lista de Clientes ({{ $clientes->count() }})
        </h6>
    </div>
    <div class="card-body">
        @if($clientes->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Fecha Registro</th>
                        <th>Total Pedidos</th>
                        <th>Total Compras</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                    <tr>
                        <td>
                            <strong>{{ $cliente['nombre'] }}</strong>
                        </td>
                        <td>{{ $cliente['email'] }}</td>
                        <td>{{ $cliente['telefono'] ?? 'N/A' }}</td>
                        <td>{{ $cliente['fecha_registro'] }}</td>
                        <td>
                            <span class="badge bg-info">{{ $cliente['total_pedidos'] }}</span>
                        </td>
                        <td>
                            <strong>S/ {{ number_format($cliente['total_compras'], 2) }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-{{ $cliente['activo'] ? 'success' : 'secondary' }}">
                                {{ $cliente['activo'] ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.clientes.show', $cliente['id']) }}" 
                                   class="btn btn-outline-primary" title="Ver detalle">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.clientes.edit', $cliente['id']) }}" 
                                   class="btn btn-outline-warning" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.clientes.toggle-activo', $cliente['id']) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-outline-{{ $cliente['activo'] ? 'warning' : 'success' }}" 
                                            title="{{ $cliente['activo'] ? 'Desactivar' : 'Activar' }}">
                                        <i class="fa fa-{{ $cliente['activo'] ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fa fa-users fa-3x text-muted mb-3"></i>
            <p class="text-muted">No hay clientes registrados en el sistema.</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Filtros -->
<div class="modal fade" id="filtrosModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtrar Clientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{ route('admin.clientes.index') }}">
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="activo">Activos</option>
                            <option value="inactivo">Inactivos</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ordenar por</label>
                        <select name="orden" class="form-select">
                            <option value="recientes">Más recientes</option>
                            <option value="antiguos">Más antiguos</option>
                            <option value="compras">Mayores compras</option>
                            <option value="pedidos">Más pedidos</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Aplicar Filtros</button>
                        <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection