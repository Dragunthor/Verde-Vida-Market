<?php
include '../includes/conexion.php';
include '../includes/funciones.php';

if (!isset($_GET['id'])) {
    header("Location: catalogo.php");
    exit();
}

$producto_id = intval($_GET['id']);

// Obtener producto
$sql = "SELECT p.*, c.nombre as categoria_nombre 
        FROM productos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.id = ? AND p.activo = 1";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $producto_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$producto = mysqli_fetch_assoc($resultado);

if (!$producto) {
    header("Location: catalogo.php");
    exit();
}

// Productos relacionados
$sql_relacionados = "SELECT * FROM productos 
                    WHERE categoria_id = ? AND id != ? AND activo = 1 
                    ORDER BY RAND() LIMIT 3";
$stmt_rel = mysqli_prepare($con, $sql_relacionados);
mysqli_stmt_bind_param($stmt_rel, 'ii', $producto['categoria_id'], $producto_id);
mysqli_stmt_execute($stmt_rel);
$relacionados = mysqli_stmt_get_result($stmt_rel);

include '../includes/header.php';
?>

<!-- Migas de pan -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="catalogo.php">Productos</a></li>
        <li class="breadcrumb-item"><a href="catalogo.php?categoria=<?php echo $producto['categoria_id']; ?>">
            <?php echo $producto['categoria_nombre']; ?>
        </a></li>
        <li class="breadcrumb-item active"><?php echo $producto['nombre']; ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-6">
        <div class="product-image">
            <?php if ($producto['imagen']): ?>
                <img src="../uploads/productos/<?php echo $producto['imagen']; ?>" 
                     class="img-fluid rounded" alt="<?php echo $producto['nombre']; ?>">
            <?php else: ?>
                <img src="../assets/images/placeholder.jpg" 
                     class="img-fluid rounded" alt="Imagen no disponible">
            <?php endif; ?>
        </div>
    </div>
    
    <div class="col-md-6">
        <h1 class="product-title"><?php echo $producto['nombre']; ?></h1>
        
        <div class="product-meta mb-3">
            <span class="badge bg-success"><?php echo $producto['categoria_nombre']; ?></span>
            <?php if ($producto['origen'] == 'Local'): ?>
                <span class="badge bg-info">Producto Local</span>
            <?php endif; ?>
        </div>
        
        <div class="product-price mb-4">
            <h2 class="text-success">S/ <?php echo number_format($producto['precio'], 2); ?></h2>
            <small class="text-muted">por <?php echo $producto['unidad']; ?></small>
        </div>
        
        <div class="product-info mb-4">
            <h5>Descripción</h5>
            <p class="text-muted"><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
        </div>
        
        <div class="product-details mb-4">
            <div class="row">
                <div class="col-6">
                    <strong>Stock disponible:</strong><br>
                    <span class="<?php echo $producto['stock'] > 0 ? 'text-success' : 'text-danger'; ?>">
                        <?php echo $producto['stock']; ?> <?php echo $producto['unidad']; ?>
                    </span>
                </div>
                <div class="col-6">
                    <strong>Origen:</strong><br>
                    <?php echo $producto['origen']; ?>
                </div>
            </div>
        </div>
        
        <?php if ($producto['stock'] > 0): ?>
            <form method="POST" action="../carrito/agregar.php" class="mb-4">
                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label for="cantidad" class="form-label"><strong>Cantidad:</strong></label>
                    </div>
                    <div class="col-auto">
                        <input type="number" class="form-control" id="cantidad" name="cantidad" 
                               value="1" min="1" max="<?php echo $producto['stock']; ?>" 
                               style="width: 100px;">
                    </div>
                    <div class="col-auto">
                        <span class="text-muted"><?php echo $producto['unidad']; ?></span>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fa fa-cart-plus"></i> Agregar al Carrito
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">
                <h5><i class="fa fa-exclamation-triangle"></i> Producto Agotado</h5>
                <p class="mb-0">Este producto no está disponible temporalmente.</p>
            </div>
        <?php endif; ?>
        
        <div class="product-actions">
            <a href="catalogo.php?categoria=<?php echo $producto['categoria_id']; ?>" 
               class="btn btn-outline-success w-100">
                <i class="fa fa-arrow-left"></i> Ver más productos de <?php echo $producto['categoria_nombre']; ?>
            </a>
        </div>
    </div>
</div>

<!-- Productos relacionados -->
<?php if (mysqli_num_rows($relacionados) > 0): ?>
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-4">Productos Relacionados</h3>
        <div class="row">
            <?php while ($relacionado = mysqli_fetch_assoc($relacionados)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($relacionado['imagen']): ?>
                            <img src="../uploads/productos/<?php echo $relacionado['imagen']; ?>" 
                                 class="card-img-top" alt="<?php echo $relacionado['nombre']; ?>">
                        <?php else: ?>
                            <img src="../assets/images/placeholder.jpg" 
                                 class="card-img-top" alt="Imagen no disponible">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $relacionado['nombre']; ?></h5>
                            <p class="card-text text-muted small">
                                <?php echo substr($relacionado['descripcion'], 0, 80); ?>...
                            </p>
                            <p class="text-success fw-bold">
                                S/ <?php echo number_format($relacionado['precio'], 2); ?>
                            </p>
                            <a href="detalle.php?id=<?php echo $relacionado['id']; ?>" 
                               class="btn btn-outline-success btn-sm">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>