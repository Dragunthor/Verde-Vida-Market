<?php
include '../includes/conexion.php';
include '../includes/funciones.php';
include '../includes/header.php';
// Procesar actualización de cantidades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_carrito'])) {
    foreach ($_POST['cantidades'] as $item_id => $cantidad) {
        $item_id = intval($item_id);
        $cantidad = intval($cantidad);
        
        if ($cantidad > 0) {
            $sql = "UPDATE carrito_temporal SET cantidad = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 'ii', $cantidad, $item_id);
            mysqli_stmt_execute($stmt);
        } else {
            // Eliminar si la cantidad es 0
            $sql = "DELETE FROM carrito_temporal WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $item_id);
            mysqli_stmt_execute($stmt);
        }
    }
    $_SESSION['mensaje'] = "Carrito actualizado correctamente.";
}

// Procesar eliminación de item
if (isset($_GET['eliminar'])) {
    $item_id = intval($_GET['eliminar']);
    $sql = "DELETE FROM carrito_temporal WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $item_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['mensaje'] = "Producto eliminado del carrito.";
}

$carrito = obtenerCarrito($con);
$total = 0;

?>

<div class="row">
    <div class="col-md-8">
        <h2><i class="fa fa-shopping-cart"></i> Mi Carrito de Compras</h2>
        
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['mensaje']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        
        <?php if (mysqli_num_rows($carrito) > 0): ?>
            <form method="POST" action="">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-success">
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = mysqli_fetch_assoc($carrito)): 
                                $subtotal = $item['precio'] * $item['cantidad'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['imagen']): ?>
                                                <img src="../uploads/productos/<?php echo $item['imagen']; ?>" 
                                                     alt="<?php echo $item['nombre']; ?>" 
                                                     class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                            <?php else: ?>
                                                <img src="../assets/images/placeholder.jpg" 
                                                     alt="Imagen no disponible" 
                                                     class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?php echo $item['nombre']; ?></h6>
                                                <small class="text-muted"><?php echo $item['unidad']; ?></small>
                                                <?php if ($item['stock'] < $item['cantidad']): ?>
                                                    <div class="text-danger small">
                                                        <i class="fa fa-exclamation-triangle"></i>
                                                        Stock insuficiente (<?php echo $item['stock']; ?> disponible)
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        S/ <?php echo number_format($item['precio'], 2); ?>
                                    </td>
                                    <td class="align-middle">
                                        <input type="number" name="cantidades[<?php echo $item['id']; ?>]" 
                                               value="<?php echo $item['cantidad']; ?>" 
                                               min="0" max="<?php echo $item['stock']; ?>" 
                                               class="form-control" style="width: 80px;">
                                    </td>
                                    <td class="align-middle">
                                        <strong>S/ <?php echo number_format($subtotal, 2); ?></strong>
                                    </td>
                                    <td class="align-middle">
                                        <a href="?eliminar=<?php echo $item['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('¿Estás seguro de eliminar este producto del carrito?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between mb-4">
                    <a href="../productos/catalogo.php" class="btn btn-outline-success">
                        <i class="fa fa-arrow-left"></i> Seguir Comprando
                    </a>
                    <button type="submit" name="actualizar_carrito" class="btn btn-warning">
                        <i class="fa fa-refresh"></i> Actualizar Carrito
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h3>Tu carrito está vacío</h3>
                <p class="text-muted mb-4">¡Descubre nuestros deliciosos productos orgánicos!</p>
                <a href="../productos/catalogo.php" class="btn btn-success btn-lg">
                    <i class="fa fa-shopping-bag"></i> Comprar Ahora
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <?php if (mysqli_num_rows($carrito) > 0): ?>
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fa fa-receipt"></i> Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span>S/ <?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Envío:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total:</strong>
                        <strong class="h5 text-success">S/ <?php echo number_format($total, 2); ?></strong>
                    </div>
                    
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <a href="../pedidos/checkout.php" class="btn btn-success w-100 btn-lg">
                            <i class="fa fa-credit-card"></i> Proceder al Pago
                        </a>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <p class="mb-2"><strong>¿Ya tienes cuenta?</strong></p>
                            <a href="../auth/login.php?redirect=carrito" class="btn btn-outline-success btn-sm w-100 mb-2">
                                Iniciar Sesión
                            </a>
                            <p class="mb-2 mt-3"><strong>¿Nuevo cliente?</strong></p>
                            <a href="../auth/registro.php" class="btn btn-success btn-sm w-100">
                                Crear Cuenta
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>