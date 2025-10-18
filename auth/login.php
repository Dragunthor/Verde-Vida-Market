<?php
include '../includes/conexion.php';
include '../includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    
    $usuario = verificarLogin($con, $email, $password);
    
    if ($usuario) {
        // Iniciar sesión
        session_start();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        
        // Migrar carrito temporal si existe
        migrarCarrito($con, $usuario['id']);
        
        // Redireccionar según el rol
        if ($usuario['rol'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        $error = "Email o contraseña incorrectos.";
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fa fa-sign-in"></i> Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">Iniciar Sesión</button>
                </form>
                
                <div class="text-center mt-3">
                    <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
                    <p><a href="recuperar_password.php">¿Olvidaste tu contraseña?</a></p>
                </div>
                
                <!-- Cuenta de demostración -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6>Demo - Cuenta de Administrador:</h6>
                    <p class="mb-1"><strong>Email:</strong> admin@verdevida.com</p>
                    <p class="mb-0"><strong>Contraseña:</strong> Admin123</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>