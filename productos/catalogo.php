<?php
include '../includes/conexion.php';
include '../includes/funciones.php';

// Parámetros de filtrado
$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;
$busqueda = isset($_GET['busqueda']) ? mysqli_real_escape_string($con, $_GET['busqueda']) : null;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$productos_por_pagina = 9;

// Obtener productos con filtros
$resultado = obtenerProductos($con, $categoria_id, $busqueda, $productos_por_pagina, ($pagina - 1) * $productos_por_pagina);

// Obtener total para paginación
$sql_count = "SELECT COUNT(*) as total FROM productos p WHERE p.activo = 1";
if ($categoria_id) $sql_count .= " AND p.categoria_id = $categoria_id";
if ($busqueda) $sql_count .= " AND (p.nombre LIKE '%$busqueda%' OR p.descripcion LIKE '%$busqueda%')";
$total_resultados = mysqli_fetch_assoc(mysqli_query($con, $sql_count))['total'];
$total_paginas = ceil($total_resultados / $productos_por_pagina);

// Obtener categorías para el filtro
$categorias = obtenerCategorias($con);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-filter"></i> Filtrar Productos</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="busqueda" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="busqueda" name="busqueda" 
                               value="<?php echo htmlspecialchars($busqueda); ?>" 
                               placeholder="Nombre del producto...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria">
                            <option value="">Todas las categorías</option>
                            <?php while ($categoria = mysqli_fetch_assoc($categorias)): ?>
                                <option value="<?php echo $categoria['id']; ?>" 
                                    <?php echo ($categoria_id == $categoria['id']) ? 'selected' : ''; ?>>
                                    <?php echo $categoria['nombre']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa fa-search"></i> Aplicar Filtros
                    </button>
                    <a href="catalogo.php" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fa fa-refresh"></i> Limpiar
                    </a>
                </form>
            </div>
        </div>
        
        <!-- Información -->
        <div class="card">
            <div class="card-body">
                <h6>¿Por qué comprar orgánico?</h6>
                <ul class="list-unstyled small">
                    <li><i class="fa fa-check text-success"></i> Más nutritivos</li>
                    <li><i class="fa fa-check text-success"></i> Sin pesticidas</li>
                    <li><i class="fa fa-check text-success"></i> Mejor sabor</li>
                    <li><i class="fa fa-check text-success"></i> Apoyo local</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <!-- Encabezado y resultados -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <?php
                if ($categoria_id) {
                    $cat_result = mysqli_query($con, "SELECT nombre FROM categorias WHERE id = $categoria_id");
                    $cat_nombre = mysqli_fetch_assoc($cat_result)['nombre'];
                    echo "Productos: " . $cat_nombre;
                } elseif ($busqueda) {
                    echo "Búsqueda: \"" . htmlspecialchars($busqueda) . "\"";
                } else {
                    echo "Todos los Productos";
                }
                ?>
            </h2>
            <span class="text-muted"><?php echo $total_resultados; ?> productos encontrados</span>
        </div>

        <!-- Productos -->
        <div class="row">
            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <?php while ($producto = mysqli_fetch_assoc($resultado)): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 product-card">
                            <div class="position-relative">
                                <?php if ($producto['imagen']): ?>
                                    <img src="../uploads/productos/<?php echo $producto['imagen']; ?>" 
                                         class="card-img-top" alt="<?php echo $producto['nombre']; ?>">
                                <?php else: ?>
                                    <img src="../assets/images/placeholder.jpg" 
                                         class="card-img-top" alt="Imagen no disponible">
                                <?php endif; ?>
                                
                                <?php if ($producto['stock'] <= 0): ?>
                                    <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1">
                                        Agotado
                                    </div>
                                <?php elseif ($producto['origen'] == 'Local'): ?>
                                    <div class="position-absolute top-0 start-0 bg-success text-white px-2 py-1">
                                        Local
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                                <p class="card-text flex-grow-1 text-muted small">
                                    <?php echo substr($producto['descripcion'], 0, 100); ?>...
                                </p>
                                
                                <div class="mt-auto">
                                    <p class="text-success fw-bold h5 mb-2">
                                        S/ <?php echo number_format($producto['precio'], 2); ?>
                                        <small class="text-muted">/<?php echo $producto['unidad']; ?></small>
                                    </p>
                                    
                                    <?php if ($producto['stock'] > 0): ?>
                                    <form method="POST" action="../carrito/agregar.php" class="mt-2">
                                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                                        
                                        <div class="row align-items-center g-2">
                                            <div class="col-6">
                                                <a href="detalle.php?id=<?php echo $producto['id']; ?>" 
                                                   class="btn btn-outline-success btn-sm w-100">
                                                    <i class="fa fa-eye"></i> Detalles
                                                </a>
                                            </div>
                                            
                                            <div class="col-3">
                                                <input type="number" class="form-control form-control-sm" 
                                                       name="cantidad" value="1" min="1" 
                                                       max="<?php echo $producto['stock']; ?>" 
                                                       aria-label="Cantidad">
                                            </div>
                                            
                                            <div class="col-3">
                                                <button type="submit" class="btn btn-success btn-sm w-100" 
                                                        title="Agregar al carrito">
                                                    <i class="fa fa-cart-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center mt-1">
                                            <small class="text-muted">
                                                Stock: <?php echo $producto['stock']; ?> <?php echo $producto['unidad']; ?>
                                            </small>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <a href="detalle.php?id=<?php echo $producto['id']; ?>" 
                                           class="btn btn-outline-success btn-sm">
                                            <i class="fa fa-eye"></i> Detalles
                                        </a>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            Sin Stock
                                        </button>
                                    </div>
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fa fa-search fa-3x text-muted mb-3"></i>
                        <h4>No se encontraron productos</h4>
                        <p class="text-muted">Intenta con otros filtros de búsqueda</p>
                        <a href="catalogo.php" class="btn btn-success">Ver todos los productos</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Paginación -->
        <?php if ($total_paginas > 1): ?>
            <nav aria-label="Paginación de productos">
                <ul class="pagination justify-content-center">
                    <?php if ($pagina > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])); ?>">
                                Anterior
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo $i == $pagina ? 'active' : ''; ?>">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($pagina < $total_paginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])); ?>">
                                Siguiente
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>