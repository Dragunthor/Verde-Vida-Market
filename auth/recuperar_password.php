<?php
include '../includes/conexion.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // Verificar si el email existe
    $sql = "SELECT id, nombre FROM usuarios WHERE email = ? AND activo = 1";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($resultado);
    
    if ($usuario) {
        $mensaje = "Se ha enviado un enlace de recuperación a tu email.";
        // En un sistema real, aquí se enviaría un email con un enlace seguro
    } else {
        $error = "No existe una cuenta con ese email.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fa fa-key"></i> Recuperar Contraseña</h4>
            </div>
            <div class="card-body">
                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-success">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <p>Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.</p>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">Enviar Enlace de Recuperación</button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="login.php">Volver al inicio de sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
mysqli_close($con);
?>