<?php
include 'includes/header.php';
include 'includes/conexion.php';
?>

<div class="hero-section bg-success text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="display-4">Bienvenido a VerdeVida Market</h1>
        <p class="lead">Productos orgánicos frescos directamente del campo a tu mesa</p>
        <a href="productos/catalogo.php" class="btn btn-light btn-lg mt-3">Ver Productos</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <h2>Productos Destacados</h2>
        <div class="row">
            <?php
            $sql = "SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    WHERE p.activo = 1 
                    ORDER BY p.created_at DESC 
                    LIMIT 6";
            $resultado = mysqli_query($con, $sql);
            
            while ($producto = mysqli_fetch_assoc($resultado)):
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($producto['imagen']): ?>
                        <img src="/VerdeVida Market/uploads/productos/<?php echo $producto['imagen']; ?>" class="card-img-top" alt="<?php echo $producto['nombre']; ?>">
                    <?php else: ?>
                        <img src="/VerdeVida Market/assets/images/placeholder.jpg" class="card-img-top" alt="Imagen no disponible">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                        <p class="card-text"><?php echo substr($producto['descripcion'], 0, 100); ?>...</p>
                        <p class="text-success fw-bold">S/ <?php echo $producto['precio']; ?> por <?php echo $producto['unidad']; ?></p>
                        <a href="productos/detalle.php?id=<?php echo $producto['id']; ?>" class="btn btn-success">Ver Detalles</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <div class="col-md-4">
        <h3>Categorías</h3>
        <div class="list-group">
            <?php
            $sql_categorias = "SELECT * FROM categorias ORDER BY nombre";
            $result_categorias = mysqli_query($con, $sql_categorias);
            
            while ($categoria = mysqli_fetch_assoc($result_categorias)):
            ?>
            <a href="productos/catalogo.php?categoria=<?php echo $categoria['id']; ?>" class="list-group-item list-group-item-action">
                <?php echo $categoria['nombre']; ?>
            </a>
            <?php endwhile; ?>
        </div>
        
        <div class="mt-4">
            <h4>¿Por qué elegirnos?</h4>
            <ul class="list-unstyled">
                <li><i class="fa fa-check text-success"></i> Productos 100% orgánicos</li>
                <li><i class="fa fa-check text-success"></i> Cultivo local y sostenible</li>
                <li><i class="fa fa-check text-success"></i> Entregas a domicilio</li>
                <li><i class="fa fa-check text-success"></i> Precios justos</li>
            </ul>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
mysqli_close($con);
?>