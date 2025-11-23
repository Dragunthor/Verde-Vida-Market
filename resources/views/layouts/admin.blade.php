<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - @yield('title', 'VerdeVida Market')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .admin-sidebar {
            background-color: #2c3e50;
            min-height: 100vh;
            padding: 0;
            transition: all 0.3s;
        }
        .admin-sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-bottom: 1px solid #34495e;
            transition: all 0.3s;
        }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            background-color: #34495e;
            color: #fff;
            padding-left: 25px;
        }
        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
        .admin-sidebar .nav-link .badge {
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
            background-color: #27ae60;
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

        /* Notificaciones flotantes */
        .notification-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
            max-width: 350px;
        }
        .notification {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
            margin-bottom: 10px;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .notification.fade-out {
            animation: slideOut 0.3s ease-in forwards;
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar">
                <div class="sidebar-sticky">
                    <div class="sidebar-header">
                        <h4>
                            <i class="fa fa-leaf"></i><br>
                            VerdeVida<br>
                            <small>Admin Panel</small>
                        </h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </a>
                        </li>
                        
                        <!-- Gestión de Vendedores -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.vendedores*') ? 'active' : '' }}" 
                               href="{{ route('admin.vendedores') }}">
                                <i class="fa fa-users"></i> Vendedores
                                @if(isset($vendedoresPendientesCount) && $vendedoresPendientesCount > 0)
                                    <span class="badge bg-warning">{{ $vendedoresPendientesCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Gestión de Productos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.productos*') ? 'active' : '' }}" 
                               href="{{ route('admin.productos.index') }}">
                                <i class="fa fa-cube"></i> Productos
                                @if(isset($productosPendientesCount) && $productosPendientesCount > 0)
                                    <span class="badge bg-warning">{{ $productosPendientesCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Gestión de Categorías -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categorias*') ? 'active' : '' }}" 
                               href="{{ route('admin.categorias.index') }}">
                                <i class="fa fa-tags"></i> Categorías
                            </a>
                        </li>

                        <!-- Gestión de Pedidos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.pedidos*') ? 'active' : '' }}" 
                               href="{{ route('admin.pedidos') }}">
                                <i class="fa fa-shopping-cart"></i> Pedidos
                            </a>
                        </li>

                        <!-- NUEVA SECCIÓN: Ventas y Comisiones -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.ventas*') ? 'active' : '' }}" 
                               href="{{ route('admin.ventas') }}">
                                <i class="fa fa-money"></i> Ventas & Comisiones
                            </a>
                        </li>

                        <!-- Gestión de Usuarios/Clientes -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}" 
                               href="{{ route('admin.clientes.index') }}">
                                <i class="fa fa-user"></i> Usuarios
                            </a>
                        </li>

                        <!-- Sistema de Reportes -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reportes*') ? 'active' : '' }}" 
                               href="{{ route('admin.reportes') }}">
                                <i class="fa fa-flag"></i> Reportes
                                @if(isset($reportesPendientesCount) && $reportesPendientesCount > 0)
                                    <span class="badge bg-danger">{{ $reportesPendientesCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Moderación de Reseñas -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.resenas*') ? 'active' : '' }}" 
                               href="{{ route('admin.resenas') }}">
                                <i class="fa fa-star"></i> Reseñas
                                @if(isset($resenasPendientesCount) && $resenasPendientesCount > 0)
                                    <span class="badge bg-warning">{{ $resenasPendientesCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Configuración -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.configuracion*') ? 'active' : '' }}" 
                               href="{{ route('admin.configuracion') }}">
                                <i class="fa fa-cog"></i> Configuración
                            </a>
                        </li>

                        <!-- Separador -->
                        <li class="nav-item mt-3">
                            <hr class="bg-secondary">
                        </li>

                        <!-- Enlaces Rápidos -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                <i class="fa fa-external-link"></i> Ver Tienda
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
                            <span class="badge bg-success">{{ auth()->user()->rol }}</span>
                        </div>
                    </div>
                </nav>

                <!-- Contenedor de notificaciones flotantes -->
                <div class="notification-container">
                    @if(session('success'))
                        <div class="notification alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-check-circle me-2"></i>
                                <div class="flex-grow-1">{{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="notification alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                <div class="flex-grow-1">{{ session('error') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="notification alert alert-warning alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-info-circle me-2"></i>
                                <div class="flex-grow-1">{{ session('warning') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="px-4 py-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-cerrar notificaciones después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            
            notifications.forEach(notification => {
                // Cerrar automáticamente después de 5 segundos
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.classList.add('fade-out');
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.remove();
                            }
                        }, 300);
                    }
                }, 5000);
                
                // Cerrar al hacer clic en la X
                const closeButton = notification.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.addEventListener('click', function() {
                        notification.classList.add('fade-out');
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.remove();
                            }
                        }, 300);
                    });
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>