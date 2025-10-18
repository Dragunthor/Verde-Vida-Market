<?php
session_start();
include '../../includes/conexion.php';
include '../../includes/funciones.php';

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Filtros
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Construir consulta con filtros
$sql = "SELECT p.*, u.nombre as cliente_nombre 
        FROM pedidos p 
        JOIN usuarios u ON p.usuario_id = u.id 
        WHERE 1=1";

$params = [];
$types = '';

if ($estado) {
    $sql .= " AND p.estado = ?";
    $params[] = $estado;
    $types .= 's';
}

if ($fecha_inicio) {
    $sql .= " AND DATE(p.fecha_pedido) >= ?";
    $params[] = $fecha_inicio;
    $types .= 's';
}

if ($fecha_fin) {
    $sql .= " AND DATE(p.fecha_pedido) <= ?";
    $params[] = $fecha_fin;
    $types .= 's';
}

$sql .= " ORDER BY p.fecha_pedido DESC";

$stmt = mysqli_prepare($con, $sql);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$pedidos = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - VerdeVida Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
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
        .badge-estado {
            font-size: 0.8em;
            padding: 6px 12px;
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
                            <a class="nav-link" href="../dashboard.php">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../productos/index.php">
                                <i class="fa fa-cube"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../categorias/index.php">
                                <i class="fa fa-tags"></i> Categorías
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">
                                <i class="fa fa-shopping-cart"></i> Pedidos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../clientes/index.php">
                                <i class="fa fa-users"></i> Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../index.php" target="_blank">
                                <i class="fa fa-external-link"></i> Ver Tienda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../auth/logout.php">
                                <i class="fa fa-sign-out"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 ml-sm-auto px-4 py-4">
                <h2 class="mb-4">Gestión de Pedidos</h2>

                <!-- Filtros -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-light">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-filter"></i> Filtros
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Estado del Pedido</label>
                                        <select class="form-select" id="estado" name="estado">
                                            <option value="">Todos los estados</option>
                                            <option value="pendiente" <?php echo $estado == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                            <option value="confirmado" <?php echo $estado == 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                                            <option value="preparando" <?php echo $estado == 'preparando' ? 'selected' : ''; ?>>Preparando</option>
                                            <option value="listo" <?php echo $estado == 'listo' ? 'selected' : ''; ?>>Listo</option>
                                            <option value="entregado" <?php echo $estado == 'entregado' ? 'selected' : ''; ?>>Entregado</option>
                                            <option value="cancelado" <?php echo $estado == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-search"></i> Aplicar Filtros
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">Limpiar</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lista de Pedidos -->
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-list"></i> Lista de Pedidos
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Pedido #</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Método Pago</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($pedido = mysqli_fetch_assoc($pedidos)): ?>
                                    <tr>
                                        <td>#<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                        <td><?php echo $pedido['cliente_nombre']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></td>
                                        <td>S/ <?php echo number_format($pedido['total'], 2); ?></td>
                                        <td>
                                            <span class="badge badge-estado bg-<?php 
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
                                        <td><?php echo ucfirst($pedido['metodo_pago']); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="gestionar.php?id=<?php echo $pedido['id']; ?>" 
                                                   class="btn btn-outline-primary" title="Gestionar">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="../../pedidos/detalle.php?id=<?php echo $pedido['id']; ?>" 
                                                   class="btn btn-outline-info" title="Ver Detalles" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
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