<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Vendedor - @yield('title', 'VerdeVida Market')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .vendedor-sidebar {
            background-color: #198754;
            min-height: 100vh;
            padding: 0;
            transition: all 0.3s;
        }
        .vendedor-sidebar .nav-link {
            color: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid #28a745;
            transition: all 0.3s;
        }
        .vendedor-sidebar .nav-link:hover, .vendedor-sidebar .nav-link.active {
            background-color: #28a745;
            color: #fff;
            padding-left: 25px;
        }
        .vendedor-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
        .vendedor-sidebar .nav-link .badge {
            float: right;
            margin-top: 2px;
        }
        .stat-card {
            border-radius: 10px;
            border: none;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2rem;
            opacity: 0.7;
        }
        .sidebar-header {
            background-color: #146c43;
            padding: 20px;
            text-align: center;
        }
        .sidebar-header h4 {
            margin: 0;
            color: white;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .navbar-top {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            padding: 15px 0;
        }
        .vendedor-badge {
            background: #ffc107;
            color: #000;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 vendedor-sidebar">
                <div class="sidebar-sticky">
                    <div class="sidebar-header">
                        <h4>
                            <i class="fa fa-store"></i><br>
                            Mi Tienda<br>
                            <small>Panel Vendedor</small>
                        </h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('vendedor.dashboard') ? 'active' : '' }}" 
                               href="{{ route('vendedor.dashboard') }}">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </a>
                        </li>
                        
                        <!-- Gestión de Productos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('vendedor.productos*') ? 'active' : '' }}" 
                               href="{{ route('vendedor.productos') }}">
                                <i class="fa fa-cube"></i> Mis Productos
                                @if(isset($productosPendientesCount) && $productosPendientesCount > 0)
                                    <span class="badge bg-warning">{{ $productosPendientesCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Gestión de Pedidos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('vendedor.pedidos*') ? 'active' : '' }}" 
                               href="{{ route('vendedor.pedidos') }}">
                                <i class="fa fa-shopping-cart"></i> Pedidos
                                @if(isset($pedidosPendientesCount) && $pedidosPendientesCount > 0)
                                    <span class="badge bg-warning">{{ $pedidosPendientesCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Reportes de Ventas -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('vendedor.ventas*') ? 'active' : '' }}" 
                               href="{{ route('vendedor.ventas') }}">
                                <i class="fa fa-chart-line"></i> Ventas
                            </a>
                        </li>

                        <!-- Separador -->
                        <li class="nav-item mt-3">
                            <hr class="bg-light">
                        </li>

                        <!-- Enlaces Rápidos -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                <i class="fa fa-external-link"></i> Ver Tienda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('perfil.edit') }}">
                                <i class="fa fa-user"></i> Mi Perfil
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link text-start w-100" style="border: none; background: none; color: inherit;">
                                    <i class="fa fa-sign-out"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 main-content px-0">
                <!-- Top Navigation -->
                <nav class="navbar-top px-4">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="mb-0 text-dark">@yield('page-title', 'Dashboard')</h5>
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">
                                <i class="fa fa-user-circle"></i> {{ auth()->user()->nombre }}
                            </span>
                            <span class="vendedor-badge">
                                <i class="fa fa-store"></i> Vendedor
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="px-4 py-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fa fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show">
                            <i class="fa fa-info-circle"></i> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>