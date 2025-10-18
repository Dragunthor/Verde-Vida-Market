<?php
session_start();
include '../includes/conexion.php';
include '../includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = intval($_POST['producto_id']);
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
    
    // Verificar que el producto existe y tiene stock
    $sql = "SELECT * FROM productos WHERE id = ? AND activo = 1 AND stock >= ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $producto_id, $cantidad);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $producto = mysqli_fetch_assoc($resultado);
    
    if ($producto) {
        if (agregarAlCarrito($con, $producto_id, $cantidad)) {
            $_SESSION['mensaje'] = "✅ " . $producto['nombre'] . " agregado al carrito correctamente.";
        } else {
            $_SESSION['error'] = "❌ Error al agregar el producto al carrito.";
        }
    } else {
        $_SESSION['error'] = "❌ Producto no disponible o stock insuficiente.";
    }
    
    // Redireccionar de vuelta a la página anterior
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?>