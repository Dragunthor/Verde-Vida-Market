<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerdeVida Market - @yield('title', 'Productos Orgánicos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 10px;
        }
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .navbar-brand {
            font-weight: bold;
        }
        footer {
            margin-top: auto;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
        }
        .vendedor-badge {
            background: #ffc107;
            color: #000;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fa fa-leaf"></i> VerdeVida Market
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('productos.index') }}">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vendedores.index') }}">Vendedores</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('carrito.index') }}">
                                <i class="fa fa-shopping-cart"></i> Carrito
                                @if(auth()->user()->carrito->count() > 0)
                                    <span class="badge bg-danger">{{ auth()->user()->carrito->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pedidos.index') }}">Mis Pedidos</a>
                        </li>
                        
                        @if(auth()->user()->esVendedor())
                            @php
                                $perfilVendedor = auth()->user()->perfilVendedor;
                                $esVendedorActivo = $perfilVendedor && $perfilVendedor->activo_vendedor;
                            @endphp
                            
                            @if($esVendedorActivo)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vendedor.dashboard') }}">
                                        <span class="vendedor-badge">Mi Tienda</span>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link text-warning" href="{{ route('vendedor.solicitud') }}">
                                        <small>Vendedor Pendiente</small>
                                    </a>
                                </li>
                            @endif
                        @endif

                        @if(auth()->user()->esAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->nombre }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('perfil.edit') }}">Mi Perfil</a></li>
                                @if(!auth()->user()->esVendedor() && !auth()->user()->esAdmin())
                                    <li><a class="dropdown-item" href="{{ route('vendedor.solicitud') }}">Ser Vendedor</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fa fa-leaf"></i> VerdeVida Market</h5>
                    <p>Productos orgánicos y locales para una vida saludable.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; {{ date('Y') }} VerdeVida Market. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>