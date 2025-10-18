<?php
session_start();
include '../includes/conexion.php';
include '../includes/funciones.php';

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: /VerdeVida Market/auth/login.php");
    exit();
}

// Obtener estadísticas
$stats = [
    'total_pedidos' => mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM pedidos"))['total'],
    'pedidos_pendientes' => mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM pedidos WHERE estado = 'pendiente'"))['total'],
    'total_clientes' => mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'cliente'"))['total'],
    'total_productos' => mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM productos"))['total'],
    'ventas_totales' => mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(total) as total FROM pedidos WHERE estado != 'cancelado'"))['total'] ?? 0,
];

// Pedidos recientes
$pedidos_recientes = mysqli_query($con, 
    "SELECT p.*, u.nombre as cliente_nombre 
     FROM pedidos p 
     JOIN usuarios u ON p.usuario_id = u.id 
     ORDER BY p.fecha_pedido DESC 
     LIMIT 5"
);

// Productos con stock bajo
$stock_bajo = mysqli_query($con, 
    "SELECT * FROM productos 
     WHERE stock < 10 AND activo = 1 
     ORDER BY stock ASC 
     LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - VerdeVida Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="/VerdeVida Market/assets/css/estilo.css" rel="stylesheet">
    <style>
        .admin-sidebar {
            background-color: #343a40;
            min-height: 100vh;
            padding: 0;
        }
        .admin-sidebar .nav-link {
            color: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid #4b545c;
        }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            background-color: #495057;
            color: #fff;
        }
        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar">
                <div class="sidebar-sticky pt-3">
                    <h4 class="text-white text-center mb-4">
                        <i class="fa fa-leaf"></i><br>
                        VerdeVida Admin
                    </h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productos/index.php">
                                <i class="fa fa-cube"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="categorias/index.php">
                                <i class="fa fa-tags"></i> Categorías
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pedidos/index.php">
                                <i class="fa fa-shopping-cart"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="clientes/index.php">
                                <i class="fa fa-users"></i> Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/VerdeVida Market/index.php" target="_blank">
                                <i class="fa fa-external-link"></i> Ver Tienda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/VerdeVida Market/auth/logout.php">
                                <i class="fa fa-sign-out"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 ml-sm-auto px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Dashboard de Administración</h2>
                    <span class="text-muted">Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?></span>
                </div>

                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Ventas Totales</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            S/ <?php echo number_format($stats['ventas_totales'], 2); ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-money stat-icon text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Pedidos</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $stats['total_pedidos']; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-shopping-cart stat-icon text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pedidos Pendientes</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $stats['pedidos_pendientes']; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-clock-o stat-icon text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Clientes</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $stats['total_clientes']; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-users stat-icon text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Pedidos Recientes -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-success text-white">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fa fa-history"></i> Pedidos Recientes
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Pedido #</th>
                                                <th>Cliente</th>
                                                <th>Fecha</th>
                                                <th>Total</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($pedido = mysqli_fetch_assoc($pedidos_recientes)): ?>
                                            <tr>
                                                <td>#<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                                <td><?php echo $pedido['cliente_nombre']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($pedido['fecha_pedido'])); ?></td>
                                                <td>S/ <?php echo number_format($pedido['total'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        switch($pedido['estado']) {
                                                            case 'pendiente': echo 'warning'; break;
                                                            case 'confirmado': echo 'info'; break;
                                                            case 'preparando': echo 'primary'; break;
                                                            case 'listo': echo 'success'; break;
                                                            case 'entregado': echo 'success'; break;
                                                            case 'cancelado': echo 'danger'; break;
                                                            default: echo 'secondary';
                                                        }
                                                    ?>">
                                                        <?php echo ucfirst($pedido['estado']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="pedidos/gestionar.php?id=<?php echo $pedido['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="pedidos/index.php" class="btn btn-success btn-sm">Ver todos los pedidos</a>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Bajo -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fa fa-exclamation-triangle"></i> Stock Bajo
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (mysqli_num_rows($stock_bajo) > 0): ?>
                                    <div class="list-group list-group-flush">
                                        <?php while ($producto = mysqli_fetch_assoc($stock_bajo)): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?php echo $producto['nombre']; ?></h6>
                                                <small class="text-muted">Stock: <?php echo $producto['stock']; ?></small>
                                            </div>
                                            <a href="productos/editar.php?id=<?php echo $producto['id']; ?>" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted text-center">Todo el stock está en niveles normales</p>
                                <?php endif; ?>
                                <a href="productos/index.php" class="btn btn-warning btn-sm mt-3 w-100">Gestionar Productos</a>
                            </div>
                        </div>

                        <!-- Acciones Rápidas -->
                        <div class="card shadow mt-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fa fa-bolt"></i> Acciones Rápidas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="productos/crear.php" class="btn btn-success">
                                        <i class="fa fa-plus"></i> Nuevo Producto
                                    </a>
                                    <a href="categorias/crear.php" class="btn btn-outline-success">
                                        <i class="fa fa-tag"></i> Nueva Categoría
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($con); ?>