@extends('layouts.admin')

@section('title', 'Moderación de Reseñas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Moderación de Reseñas</h2>
</div>

<!-- Reseñas de Productos Pendientes -->
<div class="card shadow mb-4">
    <div class="card-header bg-warning text-dark">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-cube"></i> Reseñas de Productos Pendientes
            <span class="badge bg-danger ms-2">{{ $resenasProductosPendientes->count() }}</span>
        </h6>
    </div>
    <div class="card-body">
        @if($resenasProductosPendientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Usuario</th>
                            <th>Calificación</th>
                            <th>Comentario</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resenasProductosPendientes as $resena)
                        <tr>
                            <td>
                                <strong>{{ $resena->producto->nombre }}</strong>
                            </td>
                            <td>{{ $resena->usuario->nombre }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star{{ $i <= $resena->calificacion ? '' : '-o' }}"></i>
                                        @endfor
                                    </span>
                                    <small class="text-muted ms-1">({{ $resena->calificacion }})</small>
                                </div>
                            </td>
                            <td>{{ Str::limit($resena->comentario, 50) }}</td>
                            <td>{{ $resena->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <form method="POST" action="{{ route('admin.resenas.producto.aprobar', $resena->id) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-success" title="Aprobar">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.resenas.producto.rechazar', $resena->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Rechazar">
                                            <i class="fa fa-times"></i>
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
                <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                <p class="text-muted">No hay reseñas de productos pendientes.</p>
            </div>
        @endif
    </div>
</div>

<!-- Reseñas de Vendedores Pendientes -->
<div class="card shadow">
    <div class="card-header bg-info text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-user"></i> Reseñas de Vendedores Pendientes
            <span class="badge bg-danger ms-2">{{ $resenasVendedoresPendientes->count() }}</span>
        </h6>
    </div>
    <div class="card-body">
        @if($resenasVendedoresPendientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Vendedor</th>
                            <th>Usuario</th>
                            <th>Calificación</th>
                            <th>Comentario</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resenasVendedoresPendientes as $resena)
                        <tr>
                            <td>
                                <strong>{{ $resena->vendedor->nombre }}</strong>
                            </td>
                            <td>{{ $resena->usuario->nombre }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star{{ $i <= $resena->calificacion ? '' : '-o' }}"></i>
                                        @endfor
                                    </span>
                                    <small class="text-muted ms-1">({{ $resena->calificacion }})</small>
                                </div>
                            </td>
                            <td>{{ Str::limit($resena->comentario, 50) }}</td>
                            <td>{{ $resena->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <form method="POST" action="{{ route('admin.resenas.vendedor.aprobar', $resena->id) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-success" title="Aprobar">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.resenas.vendedor.rechazar', $resena->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Rechazar">
                                            <i class="fa fa-times"></i>
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
                <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                <p class="text-muted">No hay reseñas de vendedores pendientes.</p>
            </div>
        @endif
    </div>
</div>
@endsection