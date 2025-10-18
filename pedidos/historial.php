<?php

include '../includes/conexion.php';
include '../includes/funciones.php';
include '../includes/header.php';
// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$es_admin = ($_SESSION['usuario_rol'] == 'admin');

// Obtener pedidos
if ($es_admin) {
    $sql = "SELECT p.*, u.nombre as usuario_nombre 
            FROM pedidos p 
            JOIN usuarios u ON p.usuario_id = u.id 
            ORDER BY p.fecha_pedido DESC";
} else {
    $sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC";
}

$stmt = mysqli_prepare($con, $sql);
if (!$es_admin) {
    mysqli_stmt_bind_param($stmt, 'i', $usuario_id);
}
mysqli_stmt_execute($stmt);
$pedidos = mysqli_stmt_get_result($stmt);


?>

<div class="row">
    <div class="col-12">
        <h2><i class="fa fa-history"></i> <?php echo $es_admin ? 'Todos los Pedidos' : 'Mis Pedidos'; ?></h2>
        
        <?php if (mysqli_num_rows($pedidos) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-success">
                        <tr>
                            <?php if ($es_admin): ?>
                                <th>Cliente</th>
                            <?php endif; ?>
                            <th>Pedido #</th>
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
                                <?php if ($es_admin): ?>
                                    <td><?php echo $pedido['usuario_nombre']; ?></td>
                                <?php endif; ?>
                                <td>#<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></td>
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
                                <td><?php echo ucfirst($pedido['metodo_pago']); ?></td>
                                <td>
                                    <a href="detalle.php?id=<?php echo $pedido['id']; ?>" 
                                       class="btn btn-sm btn-outline-success">
                                        <i class="fa fa-eye"></i> Ver
                                    </a>
                                    <?php if ($es_admin): ?>
                                        <a href="../admin/pedidos/gestionar.php?id=<?php echo $pedido['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-edit"></i> Gestionar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa fa-shopping-bag fa-4x text-muted mb-3"></i>
                <h3>No hay pedidos</h3>
                <p class="text-muted mb-4">¡Aún no has realizado ningún pedido!</p>
                <a href="../productos/catalogo.php" class="btn btn-success btn-lg">
                    <i class="fa fa-shopping-cart"></i> Comenzar a Comprar
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>