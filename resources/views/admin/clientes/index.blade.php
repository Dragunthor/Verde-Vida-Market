@extends('layouts.admin')

@section('title', 'Gestión de Clientes')

@section('content')
<h2 class="mb-4">Gestión de Clientes</h2>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-users"></i> Lista de Clientes
        </h6>
    </div>
    <div class="card-body">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                    <tr>
                        <td>
                            <strong>{{ $cliente['nombre'] }}</strong>
                        </td>
                        <td>{{ $cliente['email'] }}</td>
                        <td>{{ $cliente['telefono'] }}</td>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection