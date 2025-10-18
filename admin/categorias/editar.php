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

$categoria_id = intval($_GET['id']);

// Obtener categoría actual
$sql = "SELECT * FROM categorias WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'i', $categoria_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$categoria = mysqli_fetch_assoc($resultado);

if (!$categoria) {
    header("Location: index.php");
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);

    // Manejar subida de imagen
    $imagen = $categoria['imagen']; // Mantener la imagen actual por defecto
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $target_dir = "../../uploads/categorias/";
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
                if ($categoria['imagen'] && file_exists($target_dir . $categoria['imagen'])) {
                    unlink($target_dir . $categoria['imagen']);
                }
                $imagen = $new_filename;
            }
        }
    }

    $sql = "UPDATE categorias SET nombre = ?, descripcion = ?, imagen = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'sssi', $nombre, $descripcion, $imagen, $categoria_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Categoría actualizada correctamente.";
        header("Location: index.php");
        exit();
    } else {
        $error = "Error al actualizar la categoría: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría - VerdeVida Market</title>
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
                            <a class="nav-link" href="../productos/index.php">
                                <i class="fa fa-cube"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">
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
                    <h2>Editar Categoría</h2>
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
                                        <label for="nombre" class="form-label">Nombre de la Categoría *</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" 
                                               value="<?php echo htmlspecialchars($categoria['nombre']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción *</label>
                                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($categoria['descripcion']); ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="imagen" class="form-label">Imagen de la Categoría</label>
                                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                        <div class="form-text">Formatos: JPG, PNG, GIF. Máx 2MB</div>
                                    </div>

                                    <div class="text-center">
                                        <?php if ($categoria['imagen']): ?>
                                            <img id="preview" src="../../uploads/categorias/<?php echo $categoria['imagen']; ?>" 
                                                 alt="Vista previa" class="preview-img mt-3">
                                        <?php else: ?>
                                            <img id="preview" src="#" alt="Vista previa" class="preview-img mt-3" style="display: none;">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fa fa-save"></i> Actualizar Categoría
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