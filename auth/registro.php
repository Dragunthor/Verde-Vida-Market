<?php
include '../includes/conexion.php';
include '../includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $telefono = mysqli_real_escape_string($con, $_POST['telefono']);
    $direccion = mysqli_real_escape_string($con, $_POST['direccion']);
    
    $errores = [];
    
    // Validaciones
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido.";
    }
    
    if (strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }
    
    if ($password !== $confirm_password) {
        $errores[] = "Las contraseñas no coinciden.";
    }
    
    // Verificar si el email ya existe
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errores[] = "Este email ya está registrado.";
    }
    
    if (empty($errores)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (email, password, nombre, telefono, direccion, rol) 
                VALUES (?, ?, ?, ?, ?, 'cliente')";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'sssss', $email, $password_hash, $nombre, $telefono, $direccion);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensaje_exito = "¡Registro exitoso! Ahora puedes iniciar sesión.";
        } else {
            $errores[] = "Error al registrar el usuario. Intenta nuevamente.";
        }
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fa fa-user-plus"></i> Crear Cuenta</h4>
            </div>
            <div class="card-body">
                <?php if (isset($mensaje_exito)): ?>
                    <div class="alert alert-success">
                        <?php echo $mensaje_exito; ?>
                        <br>
                        <a href="login.php" class="btn btn-success btn-sm mt-2">Iniciar Sesión</a>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $error): ?>
                            <p class="mb-1">• <?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" 
                               value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Mínimo 6 caracteres</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">Registrarse</button>
                </form>
                
                <div class="text-center mt-3">
                    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>