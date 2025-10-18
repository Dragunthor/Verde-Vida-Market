<?php

include '../includes/conexion.php';
include '../includes/funciones.php';
include '../includes/header.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['redirect'] = 'checkout';
    header("Location: ../auth/login.php");
    exit();
}

// Obtener carrito y datos del usuario
$carrito = obtenerCarrito($con);
$usuario_id = $_SESSION['usuario_id'];
$usuario = obtenerUsuarioPorId($con, $usuario_id);

// Verificar que el carrito no esté vacío
if (mysqli_num_rows($carrito) === 0) {
    header("Location: ../carrito/ver.php");
    exit();
}

// Calcular total
$total = 0;
$items_carrito = [];
while ($item = mysqli_fetch_assoc($carrito)) {
    $subtotal = $item['precio'] * $item['cantidad'];
    $total += $subtotal;
    $items_carrito[] = $item;
}

// Procesar el pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo_pago = mysqli_real_escape_string($con, $_POST['metodo_pago']);
    $notas = mysqli_real_escape_string($con, $_POST['notas']);
    $direccion_entrega = mysqli_real_escape_string($con, $_POST['direccion_entrega']);
    
    // Iniciar transacción
    mysqli_begin_transaction($con);
    
    try {
        // 1. Crear pedido
        $sql_pedido = "INSERT INTO pedidos (usuario_id, total, metodo_pago, notas, fecha_entrega) 
                      VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 DAY))";
        $stmt_pedido = mysqli_prepare($con, $sql_pedido);
        mysqli_stmt_bind_param($stmt_pedido, 'idss', $usuario_id, $total, $metodo_pago, $notas);
        mysqli_stmt_execute($stmt_pedido);
        $pedido_id = mysqli_insert_id($con);
        
        // 2. Crear detalles del pedido y actualizar stock
        foreach ($items_carrito as $item) {
            // Insertar detalle
            $sql_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio) 
                           VALUES (?, ?, ?, ?)";
            $stmt_detalle = mysqli_prepare($con, $sql_detalle);
            mysqli_stmt_bind_param($stmt_detalle, 'iiid', $pedido_id, $item['producto_id'], $item['cantidad'], $item['precio']);
            mysqli_stmt_execute($stmt_detalle);
            
            // Actualizar stock
            $sql_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
            $stmt_stock = mysqli_prepare($con, $sql_stock);
            mysqli_stmt_bind_param($stmt_stock, 'ii', $item['cantidad'], $item['producto_id']);
            mysqli_stmt_execute($stmt_stock);
        }
        
        // 3. Limpiar carrito
        limpiarCarrito($con);
        
        // Confirmar transacción
        mysqli_commit($con);
        
        // Redirigir a confirmación
        header("Location: confirmacion.php?id=" . $pedido_id);
        exit();
        
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        mysqli_rollback($con);
        $error = "Error al procesar el pedido. Intenta nuevamente.";
    }
}


?>

<div class="row">
    <div class="col-md-8">
        <h2><i class="fa fa-credit-card"></i> Finalizar Compra</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Información del Pedido -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-list"></i> Resumen de tu Pedido</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items_carrito as $item): ?>
                                <tr>
                                    <td><?php echo $item['nombre']; ?></td>
                                    <td><?php echo $item['cantidad']; ?> <?php echo $item['unidad']; ?></td>
                                    <td>S/ <?php echo number_format($item['precio'], 2); ?></td>
                                    <td>S/ <?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-success">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>S/ <?php echo number_format($total, 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Formulario de Checkout -->
        <form method="POST" action="">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fa fa-truck"></i> Información de Entrega</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="direccion_entrega" class="form-label">Dirección de Entrega *</label>
                        <textarea class="form-control" id="direccion_entrega" name="direccion_entrega" 
                                  rows="3" required><?php echo $usuario['direccion']; ?></textarea>
                        <div class="form-text">Dirección donde entregaremos tu pedido</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Información de Contacto</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="<?php echo $usuario['nombre']; ?>" readonly>
                                <small class="form-text text-muted">Nombre</small>
                            </div>
                            <div class="col-md-6">
                                <input type="tel" class="form-control" value="<?php echo $usuario['telefono']; ?>" readonly>
                                <small class="form-text text-muted">Teléfono</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fa fa-credit-card"></i> Método de Pago</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="efectivo" value="efectivo" checked>
                            <label class="form-check-label" for="efectivo">
                                <strong>Efectivo</strong> - Paga cuando recibas tu pedido
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="transferencia" value="transferencia">
                            <label class="form-check-label" for="transferencia">
                                <strong>Transferencia Bancaria</strong>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="tarjeta" value="tarjeta">
                            <label class="form-check-label" for="tarjeta">
                                <strong>Tarjeta de Crédito/Débito</strong>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas Adicionales</label>
                        <textarea class="form-control" id="notas" name="notas" rows="3" 
                                  placeholder="Instrucciones especiales para la entrega..."></textarea>
                    </div>
                </div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fa fa-check-circle"></i> Confirmar Pedido - S/ <?php echo number_format($total, 2); ?>
                </button>
            </div>
        </form>
    </div>
    
    <div class="col-md-4">
        <!-- Resumen Lateral -->
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-receipt"></i> Resumen Final</h5>
            </div>
            <div class="card-body">
                <h6>Productos en tu pedido:</h6>
                <ul class="list-unstyled small mb-3">
                    <?php foreach ($items_carrito as $item): ?>
                        <li class="d-flex justify-content-between">
                            <span><?php echo $item['nombre']; ?> (x<?php echo $item['cantidad']; ?>)</span>
                            <span>S/ <?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>S/ <?php echo number_format($total, 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Envío:</span>
                    <span class="text-success">Gratis</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Impuestos:</span>
                    <span class="text-success">Incluidos</span>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong class="h5 text-success">S/ <?php echo number_format($total, 2); ?></strong>
                </div>
                
                <div class="alert alert-info small">
                    <i class="fa fa-info-circle"></i> 
                    <strong>Entrega estimada:</strong> 24-48 horas<br>
                    <strong>Horario:</strong> Lunes a Sábado 9:00 - 18:00
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>