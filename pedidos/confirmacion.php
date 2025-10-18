<?php
include '../includes/conexion.php';
include '../includes/funciones.php';
include '../includes/header.php';

// Verificar que el usuario esté logueado y tenga permiso para ver este pedido
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: historial.php");
    exit();
}

$pedido_id = intval($_GET['id']);
$usuario_id = $_SESSION['usuario_id'];

// Obtener información del pedido
$sql = "SELECT p.*, u.nombre as usuario_nombre, u.email, u.telefono 
        FROM pedidos p 
        JOIN usuarios u ON p.usuario_id = u.id 
        WHERE p.id = ? AND (p.usuario_id = ? OR ? = (SELECT id FROM usuarios WHERE rol = 'admin' LIMIT 1))";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'iii', $pedido_id, $usuario_id, $usuario_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$pedido = mysqli_fetch_assoc($resultado);

if (!$pedido) {
    header("Location: historial.php");
    exit();
}

// Obtener detalles del pedido
$sql_detalles = "SELECT dp.*, pr.nombre as producto_nombre, pr.unidad 
                FROM detalles_pedido dp 
                JOIN productos pr ON dp.producto_id = pr.id 
                WHERE dp.pedido_id = ?";
$stmt_detalles = mysqli_prepare($con, $sql_detalles);
mysqli_stmt_bind_param($stmt_detalles, 'i', $pedido_id);
mysqli_stmt_execute($stmt_detalles);
$detalles = mysqli_stmt_get_result($stmt_detalles);


?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="text-center mb-5">
            <i class="fa fa-check-circle fa-5x text-success mb-3"></i>
            <h1 class="display-4 text-success">¡Pedido Confirmado!</h1>
            <p class="lead">Tu pedido ha sido procesado exitosamente</p>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-info-circle"></i> Información del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Número de Pedido:</strong> #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></p>
                        <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></p>
                        <p><strong>Estado:</strong> 
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
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Método de Pago:</strong> <?php echo ucfirst($pedido['metodo_pago']); ?></p>
                        <p><strong>Total:</strong> S/ <?php echo number_format($pedido['total'], 2); ?></p>
                        <?php if ($pedido['fecha_entrega']): ?>
                            <p><strong>Entrega estimada:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_entrega'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fa fa-list"></i> Detalles del Pedido</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($detalle = mysqli_fetch_assoc($detalles)): ?>
                                <tr>
                                    <td><?php echo $detalle['producto_nombre']; ?></td>
                                    <td><?php echo $detalle['cantidad']; ?> <?php echo $detalle['unidad']; ?></td>
                                    <td>S/ <?php echo number_format($detalle['precio'], 2); ?></td>
                                    <td>S/ <?php echo number_format($detalle['precio'] * $detalle['cantidad'], 2); ?></td>
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
        
        <?php if (!empty($pedido['notas'])): ?>
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fa fa-sticky-note"></i> Notas del Pedido</h5>
            </div>
            <div class="card-body">
                <p class="mb-0"><?php echo nl2br(htmlspecialchars($pedido['notas'])); ?></p>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fa fa-user"></i> Información de Contacto</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nombre:</strong> <?php echo $pedido['usuario_nombre']; ?></p>
                        <p><strong>Email:</strong> <?php echo $pedido['email']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Teléfono:</strong> <?php echo $pedido['telefono']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="historial.php" class="btn btn-outline-success me-2">
                <i class="fa fa-history"></i> Ver Historial de Pedidos
            </a>
            <a href="../productos/catalogo.php" class="btn btn-success">
                <i class="fa fa-shopping-bag"></i> Seguir Comprando
            </a>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>