<?php
session_start();
include '../../includes/conexion.php';
include '../../includes/funciones.php';

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Obtener productos con paginación
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$productos_por_pagina = 10;
$inicio = ($pagina - 1) * $productos_por_pagina;

$sql = "SELECT p.*, c.nombre as categoria_nombre 
        FROM productos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        ORDER BY p.created_at DESC 
        LIMIT $inicio, $productos_por_pagina";
$productos = mysqli_query($con, $sql);

// Contar total de productos
$total_productos = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM productos"))['total'];
$total_paginas = ceil($total_productos / $productos_por_pagina);

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $sql = "UPDATE productos SET activo = 0 WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Producto eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el producto.";
    }
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - VerdeVida Market</title>
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
                            <a class="nav-link active" href="index.php">
                                <i class="fa fa-cube"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../categorias/index.php">
                                <i class="fa fa-tags"></i> Categorías
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../pedidos/index.php">
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
                    <h2>Gestión de Productos</h2>
                    <a href="crear.php" class="btn btn-success">
                        <i class="fa fa-plus"></i> Nuevo Producto
                    </a>
                </div>

                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $_SESSION['mensaje']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fa fa-cubes"></i> Lista de Productos (<?php echo $total_productos; ?>)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($producto = mysqli_fetch_assoc($productos)): ?>
                                    <tr>
                                        <td>
                                            <?php if ($producto['imagen']): ?>
                                                <img src="../../uploads/productos/<?php echo $producto['imagen']; ?>" 
                                                     alt="<?php echo $producto['nombre']; ?>" 
                                                     class="table-img">
                                            <?php else: ?>
                                                <img src="../../assets/images/placeholder.jpg" 
                                                     alt="Sin imagen" 
                                                     class="table-img">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo $producto['nombre']; ?></strong><br>
                                            <small class="text-muted"><?php echo $producto['unidad']; ?></small>
                                        </td>
                                        <td><?php echo $producto['categoria_nombre']; ?></td>
                                        <td>S/ <?php echo number_format($producto['precio'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $producto['stock'] > 10 ? 'success' : ($producto['stock'] > 0 ? 'warning' : 'danger'); ?>">
                                                <?php echo $producto['stock']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $producto['activo'] ? 'success' : 'secondary'; ?>">
                                                <?php echo $producto['activo'] ? 'Activo' : 'Inactivo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="editar.php?id=<?php echo $producto['id']; ?>" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="?eliminar=<?php echo $producto['id']; ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <?php if ($total_paginas > 1): ?>
                            <nav aria-label="Paginación de productos">
                                <ul class="pagination justify-content-center mt-4">
                                    <?php if ($pagina > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?pagina=<?php echo $pagina - 1; ?>">
                                                Anterior
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                        <li class="page-item <?php echo $i == $pagina ? 'active' : ''; ?>">
                                            <a class="page-link" href="?pagina=<?php echo $i; ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($pagina < $total_paginas): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?pagina=<?php echo $pagina + 1; ?>">
                                                Siguiente
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php mysqli_close($con); ?>