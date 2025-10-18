<?php
session_start();
include '../../includes/conexion.php';
include '../../includes/funciones.php';

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$pedido_id = intval($_GET['id']);

// Obtener información del pedido
$sql = "SELECT p.*, u.nombre as cliente_nombre, u.email, u.telefono, u.direccion 
        FROM pedidos p 
        JOIN usuarios u ON p.usuario_id = u.id 
        WHERE p.id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $pedido_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$pedido = mysqli_fetch_assoc($resultado);

if (!$pedido) {
    header("Location: index.php");
    exit();
}

// Obtener detalles del pedido
$sql_detalles = "SELECT dp.*, pr.nombre as producto_nombre, pr.unidad, pr.imagen 
                FROM detalles_pedido dp 
                JOIN productos pr ON dp.producto_id = pr.id 
                WHERE dp.pedido_id = ?";
$stmt_detalles = mysqli_prepare($con, $sql_detalles);
mysqli_stmt_bind_param($stmt_detalles, 'i', $pedido_id);
mysqli_stmt_execute($stmt_detalles);
$detalles = mysqli_stmt_get_result($stmt_detalles);

// Procesar actualización del estado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_estado = mysqli_real_escape_string($con, $_POST['estado']);
    $fecha_entrega = !empty($_POST['fecha_entrega']) ? $_POST['fecha_entrega'] : null;
    
    $sql_update = "UPDATE pedidos SET estado = ?, fecha_entrega = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($con, $sql_update);
    
    if ($fecha_entrega) {
        mysqli_stmt_bind_param($stmt_update, 'ssi', $nuevo_estado, $fecha_entrega, $pedido_id);
    } else {
        mysqli_stmt_bind_param($stmt_update, 'ssi', $nuevo_estado, $pedido['fecha_entrega'], $pedido_id);
    }
    
    if (mysqli_stmt_execute($stmt_update)) {
        $_SESSION['mensaje'] = "Estado del pedido actualizado correctamente.";
        header("Location: gestionar.php?id=" . $pedido_id);
        exit();
    } else {
        $error = "Error al actualizar el estado del pedido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Pedido - VerdeVida Market</title>
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
        .table-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestionar Pedido #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></h2>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Volver
                    </a>
                </div>

                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $_SESSION['mensaje']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <!-- Detalles del Pedido -->
                        <div class="card shadow mb-4">
                            <div class="card-header bg-success text-white">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fa fa-list"></i> Detalles del Pedido
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unit.</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($detalle = mysqli_fetch_assoc($detalles)): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($detalle['imagen']): ?>
                                                            <img src="../../uploads/productos/<?php echo $detalle['imagen']; ?>" 
                                                                 alt="<?php echo $detalle['producto_nombre']; ?>" 
                                                                 class="table-img me-3">
                                                        <?php else: ?>
                                                            <img src="../../assets/images/placeholder.jpg" 
                                                                 alt="Sin imagen" 
                                                                 class="table-img me-3">
                                                        <?php endif; ?>
                                                        <div>
                                                            <strong><?php echo $detalle['producto_nombre']; ?></strong><br>
                                                            <small class="text-muted"><?php echo $detalle['unidad']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo $detalle['cantidad']; ?></td>
                                                <td>S/ <?php echo number_format($detalle['precio'], 2); ?></td>
                                                <td><strong>S/ <?php echo number_format($detalle['precio'] * $detalle['cantidad'], 2); ?></strong></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                        <tfoot class="table-success">
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                                <td><strong>S/ <?php echo number_format($pedido['total'], 2); ?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Cliente -->
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fa fa-user"></i> Información del Cliente
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nombre:</strong> <?php echo $pedido['cliente_nombre']; ?></p>
                                        <p><strong>Email:</strong> <?php echo $pedido['email']; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Teléfono:</strong> <?php echo $pedido['telefono']; ?></p>
                                        <p><strong>Dirección:</strong> <?php echo nl2br(htmlspecialchars($pedido['direccion'])); ?></p>
                                    </div>
                                </div>
                                <?php if (!empty($pedido['notas'])): ?>
                                    <hr>
                                    <p><strong>Notas del Pedido:</strong></p>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($pedido['notas'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Gestión del Estado -->
                        <div class="card shadow">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fa fa-cog"></i> Gestión del Pedido
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Estado Actual</label>
                                        <select class="form-select" id="estado" name="estado" required>
                                            <option value="pendiente" <?php echo $pedido['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                            <option value="confirmado" <?php echo $pedido['estado'] == 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                                            <option value="preparando" <?php echo $pedido['estado'] == 'preparando' ? 'selected' : ''; ?>>Preparando</option>
                                            <option value="listo" <?php echo $pedido['estado'] == 'listo' ? 'selected' : ''; ?>>Listo para entrega</option>
                                            <option value="entregado" <?php echo $pedido['estado'] == 'entregado' ? 'selected' : ''; ?>>Entregado</option>
                                            <option value="cancelado" <?php echo $pedido['estado'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                                        <input type="datetime-local" class="form-control" id="fecha_entrega" name="fecha_entrega" 
                                               value="<?php echo $pedido['fecha_entrega'] ? date('Y-m-d\TH:i', strtotime($pedido['fecha_entrega'])) : ''; ?>">
                                    </div>

                                    <div class="mb-3">
                                        <p><strong>Información del Pedido:</strong></p>
                                        <ul class="list-unstyled small">
                                            <li><strong>Pedido #:</strong> <?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></li>
                                            <li><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></li>
                                            <li><strong>Método Pago:</strong> <?php echo ucfirst($pedido['metodo_pago']); ?></li>
                                            <li><strong>Total:</strong> S/ <?php echo number_format($pedido['total'], 2); ?></li>
                                        </ul>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fa fa-save"></i> Actualizar Estado
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Progreso del Pedido -->
                        <div class="card shadow mt-4">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fa fa-tasks"></i> Progreso del Pedido
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="progress mb-3" style="height: 25px;">
                                    <?php
                                    $progress = 0;
                                    switch($pedido['estado']) {
                                        case 'pendiente': $progress = 20; break;
                                        case 'confirmado': $progress = 40; break;
                                        case 'preparando': $progress = 60; break;
                                        case 'listo': $progress = 80; break;
                                        case 'entregado': $progress = 100; break;
                                        case 'cancelado': $progress = 0; break;
                                    }
                                    ?>
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: <?php echo $progress; ?>%" 
                                         aria-valuenow="<?php echo $progress; ?>" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?php echo $progress; ?>%
                                    </div>
                                </div>
                                <div class="text-center">
                                    <small class="text-muted">Estado actual: <?php echo ucfirst($pedido['estado']); ?></small>
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