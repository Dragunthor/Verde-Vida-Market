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

// Obtener información del pedido (mismo código que en confirmacion.php)
$sql = "SELECT p.*, u.nombre as usuario_nombre, u.email, u.telefono, u.direccion 
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
$sql_detalles = "SELECT dp.*, pr.nombre as producto_nombre, pr.unidad, pr.imagen 
                FROM detalles_pedido dp 
                JOIN productos pr ON dp.producto_id = pr.id 
                WHERE dp.pedido_id = ?";
$stmt_detalles = mysqli_prepare($con, $sql_detalles);
mysqli_stmt_bind_param($stmt_detalles, 'i', $pedido_id);
mysqli_stmt_execute($stmt_detalles);
$detalles = mysqli_stmt_get_result($stmt_detalles);


?>

<div class="row">
    <div class="col-md-8">
        <h2><i class="fa fa-file-text"></i> Detalle del Pedido #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></h2>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-cube"></i> Productos del Pedido</h5>
            </div>
            <div class="card-body">
                <?php while ($detalle = mysqli_fetch_assoc($detalles)): ?>
                    <div class="row mb-3 pb-3 border-bottom">
                        <div class="col-2">
                            <?php if ($detalle['imagen']): ?>
                                <img src="../uploads/productos/<?php echo $detalle['imagen']; ?>" 
                                     alt="<?php echo $detalle['producto_nombre']; ?>" 
                                     class="img-fluid rounded" style="max-height: 60px;">
                            <?php else: ?>
                                <img src="../assets/images/placeholder.jpg" 
                                     alt="Imagen no disponible" 
                                     class="img-fluid rounded" style="max-height: 60px;">
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-1"><?php echo $detalle['producto_nombre']; ?></h6>
                            <small class="text-muted">Cantidad: <?php echo $detalle['cantidad']; ?> <?php echo $detalle['unidad']; ?></small>
                        </div>
                        <div class="col-4 text-end">
                            <div class="h6 mb-0">S/ <?php echo number_format($detalle['precio'] * $detalle['cantidad'], 2); ?></div>
                            <small class="text-muted">S/ <?php echo number_format($detalle['precio'], 2); ?> c/u</small>
                        </div>
                    </div>
                <?php endwhile; ?>
                
                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <h5>Total: S/ <?php echo number_format($pedido['total'], 2); ?></h5>
                    </div>
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
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fa fa-info-circle"></i> Información del Pedido</h5>
            </div>
            <div class="card-body">
                <p><strong>Estado:</strong><br>
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
                
                <p><strong>Fecha del Pedido:</strong><br>
                    <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?>
                </p>
                
                <?php if ($pedido['fecha_entrega']): ?>
                <p><strong>Entrega Estimada:</strong><br>
                    <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_entrega'])); ?>
                </p>
                <?php endif; ?>
                
                <p><strong>Método de Pago:</strong><br>
                    <?php echo ucfirst($pedido['metodo_pago']); ?>
                </p>
                
                <hr>
                
                <h6>Información de Contacto</h6>
                <p class="mb-1"><strong>Nombre:</strong> <?php echo $pedido['usuario_nombre']; ?></p>
                <p class="mb-1"><strong>Email:</strong> <?php echo $pedido['email']; ?></p>
                <p class="mb-1"><strong>Teléfono:</strong> <?php echo $pedido['telefono']; ?></p>
                <p class="mb-0"><strong>Dirección:</strong> <?php echo nl2br(htmlspecialchars($pedido['direccion'])); ?></p>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <a href="historial.php" class="btn btn-outline-success w-100">
                <i class="fa fa-arrow-left"></i> Volver al Historial
            </a>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>