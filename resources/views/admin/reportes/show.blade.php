@extends('layouts.admin')

@section('title', 'Reporte #' . $reporte->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Reporte #{{ $reporte->id }}</h2>
    <a href="{{ route('admin.reportes') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-flag"></i> Información del Reporte
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>#{{ $reporte->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Título:</strong></td>
                        <td>{{ $reporte->titulo }}</td>
                    </tr>
                    <tr>
                        <td><strong>Descripción:</strong></td>
                        <td>{{ $reporte->descripcion }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tipo:</strong></td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($reporte->tipo) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Objeto ID:</strong></td>
                        <td>{{ $reporte->objeto_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Estado:</strong></td>
                        <td>
                            <span class="badge bg-{{ $reporte->estado == 'pendiente' ? 'warning' : ($reporte->estado == 'en_revision' ? 'info' : 'success') }}">
                                {{ ucfirst($reporte->estado) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Fecha de Creación:</strong></td>
                        <td>{{ $reporte->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Información del Objeto Reportado -->
        @if($objetoReportado)
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-info-circle"></i> Información del {{ ucfirst($reporte->tipo) }} Reportado
                </h6>
            </div>
            <div class="card-body">
                @if($reporte->tipo == 'producto')
                    <p><strong>Nombre:</strong> {{ $objetoReportado->nombre }}</p>
                    <p><strong>Precio:</strong> S/ {{ number_format($objetoReportado->precio, 2) }}</p>
                    <p><strong>Stock:</strong> {{ $objetoReportado->stock }}</p>
                    <p><strong>Vendedor:</strong> {{ $objetoReportado->vendedor->nombre ?? 'N/A' }}</p>
                @elseif($reporte->tipo == 'vendedor')
                    <p><strong>Nombre:</strong> {{ $objetoReportado->nombre }}</p>
                    <p><strong>Email:</strong> {{ $objetoReportado->email }}</p>
                    <p><strong>Teléfono:</strong> {{ $objetoReportado->telefono ?? 'N/A' }}</p>
                @elseif($reporte->tipo == 'pedido')
                    <p><strong>Pedido #:</strong> {{ $objetoReportado->id }}</p>
                    <p><strong>Total:</strong> S/ {{ number_format($objetoReportado->total, 2) }}</p>
                    <p><strong>Estado:</strong> {{ ucfirst($objetoReportado->estado) }}</p>
                    <p><strong>Cliente:</strong> {{ $objetoReportado->usuario->nombre }}</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Mensajes del Reporte -->
        @if($reporte->mensajes->count() > 0)
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-comments"></i> Mensajes
                </h6>
            </div>
            <div class="card-body">
                @foreach($reporte->mensajes as $mensaje)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $mensaje->usuario->nombre }}</strong>
                        <small class="text-muted">{{ $mensaje->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <p class="mb-0 mt-2">{{ $mensaje->mensaje }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Información del Usuario -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-user"></i> Usuario que Reporta
                </h6>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ $reporte->usuario->nombre }}</p>
                <p><strong>Email:</strong> {{ $reporte->usuario->email }}</p>
                <p><strong>Teléfono:</strong> {{ $reporte->usuario->telefono ?? 'N/A' }}</p>
                <p><strong>Registro:</strong> {{ $reporte->usuario->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Gestión del Estado -->
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="fa fa-cog"></i> Gestión del Reporte
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.reportes.estado', $reporte->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="estado" class="form-label">Cambiar Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="pendiente" {{ $reporte->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_revision" {{ $reporte->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                            <option value="resuelto" {{ $reporte->estado == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa fa-save"></i> Actualizar Estado
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection