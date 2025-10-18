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

$producto_id = intval($_GET['id']);

// Obtener producto actual
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $producto_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$producto = mysqli_fetch_assoc($resultado);

if (!$producto) {
    header("Location: index.php");
    exit();
}

// Obtener categorías para el select
$categorias = mysqli_query($con, "SELECT * FROM categorias ORDER BY nombre");

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $unidad = mysqli_real_escape_string($con, $_POST['unidad']);
    $origen = mysqli_real_escape_string($con, $_POST['origen']);
    $categoria_id = intval($_POST['categoria_id']);
    $activo = isset($_POST['activo']) ? 1 : 0;

    // Manejar subida de imagen
    $imagen = $producto['imagen']; // Mantener la imagen actual por defecto
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $target_dir = "../../uploads/productos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
                // Eliminar imagen anterior si existe
                if ($producto['imagen'] && file_exists($target_dir . $producto['imagen'])) {
                    unlink($target_dir . $producto['imagen']);
                }
                $imagen = $new_filename;
            }
        }
    }

    $sql = "UPDATE productos SET categoria_id = ?, nombre = ?, descripcion = ?, precio = ?, imagen = ?, stock = ?, unidad = ?, origen = ?, activo = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'issdsisssi', $categoria_id, $nombre, $descripcion, $precio, $imagen, $stock, $unidad, $origen, $activo, $producto_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Producto actualizado correctamente.";
        header("Location: index.php");
        exit();
    } else {
        $error = "Error al actualizar el producto: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - VerdeVida Market</title>
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
        .preview-img {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
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
                    <h2>Editar Producto</h2>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Volver
                    </a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre del Producto *</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" 
                                               value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción *</label>
                                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="precio" class="form-label">Precio (S/) *</label>
                                                <input type="number" class="form-control" id="precio" name="precio" 
                                                       value="<?php echo $producto['precio']; ?>" step="0.01" min="0" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stock" class="form-label">Stock *</label>
                                                <input type="number" class="form-control" id="stock" name="stock" 
                                                       value="<?php echo $producto['stock']; ?>" min="0" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="unidad" class="form-label">Unidad de Medida *</label>
                                                <select class="form-select" id="unidad" name="unidad" required>
                                                    <option value="">Seleccionar unidad</option>
                                                    <option value="Kg" <?php echo $producto['unidad'] == 'Kg' ? 'selected' : ''; ?>>Kilogramo (Kg)</option>
                                                    <option value="g" <?php echo $producto['unidad'] == 'g' ? 'selected' : ''; ?>>Gramo (g)</option>
                                                    <option value="L" <?php echo $producto['unidad'] == 'L' ? 'selected' : ''; ?>>Litro (L)</option>
                                                    <option value="ml" <?php echo $producto['unidad'] == 'ml' ? 'selected' : ''; ?>>Mililitro (ml)</option>
                                                    <option value="Unidad" <?php echo $producto['unidad'] == 'Unidad' ? 'selected' : ''; ?>>Unidad</option>
                                                    <option value="Paquete" <?php echo $producto['unidad'] == 'Paquete' ? 'selected' : ''; ?>>Paquete</option>
                                                    <option value="Docena" <?php echo $producto['unidad'] == 'Docena' ? 'selected' : ''; ?>>Docena</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="origen" class="form-label">Origen *</label>
                                                <select class="form-select" id="origen" name="origen" required>
                                                    <option value="">Seleccionar origen</option>
                                                    <option value="Local" <?php echo $producto['origen'] == 'Local' ? 'selected' : ''; ?>>Local</option>
                                                    <option value="Nacional" <?php echo $producto['origen'] == 'Nacional' ? 'selected' : ''; ?>>Nacional</option>
                                                    <option value="Importado" <?php echo $producto['origen'] == 'Importado' ? 'selected' : ''; ?>>Importado</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="categoria_id" class="form-label">Categoría *</label>
                                                <select class="form-select" id="categoria_id" name="categoria_id" required>
                                                    <option value="">Seleccionar categoría</option>
                                                    <?php while ($categoria = mysqli_fetch_assoc($categorias)): ?>
                                                        <option value="<?php echo $categoria['id']; ?>" 
                                                            <?php echo $producto['categoria_id'] == $categoria['id'] ? 'selected' : ''; ?>>
                                                            <?php echo $categoria['nombre']; ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Estado</label>
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                                                           <?php echo $producto['activo'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="activo">Producto activo</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="imagen" class="form-label">Imagen del Producto</label>
                                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                        <div class="form-text">Formatos: JPG, PNG, GIF. Máx 2MB</div>
                                    </div>

                                    <div class="text-center">
                                        <?php if ($producto['imagen']): ?>
                                            <img id="preview" src="../../uploads/productos/<?php echo $producto['imagen']; ?>" 
                                                 alt="Vista previa" class="preview-img mt-3">
                                        <?php else: ?>
                                            <img id="preview" src="../../assets/images/placeholder.jpg" 
                                                 alt="Vista previa" class="preview-img mt-3">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fa fa-save"></i> Actualizar Producto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Vista previa de imagen
        document.getElementById('imagen').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>

<?php mysqli_close($con); ?>