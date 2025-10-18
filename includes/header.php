<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerdeVida Market - Productos Orgánicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/estilo.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/VerdeVida Market/index.php">
                <i class="fa fa-leaf"></i> VerdeVida Market
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/VerdeVida Market/index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/VerdeVida Market/productos/catalogo.php">Productos</a>
                    </li>
                    <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/VerdeVida Market/admin/dashboard.php">Panel Admin</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/VerdeVida Market/carrito/ver.php">
                                <i class="fa fa-shopping-cart"></i> Carrito
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/VerdeVida Market/pedidos/historial.php">Mis Pedidos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user"></i> <?php echo $_SESSION['usuario_nombre']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($_SESSION['usuario_rol'] == 'admin'): ?>
                                    <li><a class="dropdown-item" href="/VerdeVida Market/admin/dashboard.php">
                                        <i class="fa fa-cog"></i> Panel Admin
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="/VerdeVida Market/auth/logout.php">
                                    <i class="fa fa-sign-out"></i> Cerrar Sesión
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/VerdeVida Market/auth/login.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/VerdeVida Market/auth/registro.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">