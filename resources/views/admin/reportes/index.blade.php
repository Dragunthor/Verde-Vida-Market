@extends('layouts.admin')

@section('title', 'Gestión de Reportes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Reportes</h2>
</div>

<ul class="nav nav-tabs" id="reportesTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button" role="tab">
            Pendientes <span class="badge bg-danger">{{ $reportesPendientes->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="revision-tab" data-bs-toggle="tab" data-bs-target="#revision" type="button" role="tab">
            En Revisión <span class="badge bg-warning">{{ $reportesEnRevision->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="resueltos-tab" data-bs-toggle="tab" data-bs-target="#resueltos" type="button" role="tab">
            Resueltos <span class="badge bg-success">{{ $reportesResueltos->count() }}</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="reportesTabContent">
    <!-- Reportes Pendientes -->
    <div class="tab-pane fade show active" id="pendientes" role="tabpanel">
        @if($reportesPendientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportesPendientes as $reporte)
                        <tr>
                            <td>#{{ $reporte->id }}</td>
                            <td>{{ $reporte->titulo }}</td>
                            <td>{{ $reporte->usuario->nombre }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($reporte->tipo) }}</span>
                            </td>
                            <td>{{ $reporte->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.reportes.show', $reporte->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-flag fa-3x text-muted mb-3"></i>
                <p class="text-muted">No hay reportes pendientes.</p>
            </div>
        @endif
    </div>

    <!-- Reportes En Revisión -->
    <div class="tab-pane fade" id="revision" role="tabpanel">
        @if($reportesEnRevision->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportesEnRevision as $reporte)
                        <tr>
                            <td>#{{ $reporte->id }}</td>
                            <td>{{ $reporte->titulo }}</td>
                            <td>{{ $reporte->usuario->nombre }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($reporte->tipo) }}</span>
                            </td>
                            <td>{{ $reporte->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.reportes.show', $reporte->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-flag fa-3x text-muted mb-3"></i>
                <p class="text-muted">No hay reportes en revisión.</p>
            </div>
        @endif
    </div>

    <!-- Reportes Resueltos -->
    <div class="tab-pane fade" id="resueltos" role="tabpanel">
        @if($reportesResueltos->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportesResueltos as $reporte)
                        <tr>
                            <td>#{{ $reporte->id }}</td>
                            <td>{{ $reporte->titulo }}</td>
                            <td>{{ $reporte->usuario->nombre }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($reporte->tipo) }}</span>
                            </td>
                            <td>{{ $reporte->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.reportes.show', $reporte->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-flag fa-3x text-muted mb-3"></i>
                <p class="text-muted">No hay reportes resueltos.</p>
            </div>
        @endif
    </div>
</div>
@endsection