<?php
// Funciones generales para VerdeVida Market

function obtenerCategorias($conexion) {
    $sql = "SELECT * FROM categorias ORDER BY nombre";
    return mysqli_query($conexion, $sql);
}

function obtenerUsuarioPorId($conexion, $id) {
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($resultado);
}

function verificarLogin($conexion, $email, $password) {
    $sql = "SELECT * FROM usuarios WHERE email = ? AND activo = 1";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($resultado);
    
    if ($usuario && password_verify($password, $usuario['password'])) {
        return $usuario;
    }
    return false;
}

function agregarAlCarrito($conexion, $producto_id, $cantidad = 1) {
    $sesion_id = session_id();
    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;
    
    // Verificar si el producto ya está en el carrito
    $sql = "SELECT * FROM carrito_temporal 
            WHERE producto_id = ? AND (sesion_id = ? OR usuario_id = ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'isi', $producto_id, $sesion_id, $usuario_id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($resultado) > 0) {
        // Actualizar cantidad
        $sql = "UPDATE carrito_temporal SET cantidad = cantidad + ? 
                WHERE producto_id = ? AND (sesion_id = ? OR usuario_id = ?)";
    } else {
        // Insertar nuevo
        $sql = "INSERT INTO carrito_temporal (sesion_id, usuario_id, producto_id, cantidad) 
                VALUES (?, ?, ?, ?)";
    }
    
    $stmt = mysqli_prepare($conexion, $sql);
    if (mysqli_num_rows($resultado) > 0) {
        mysqli_stmt_bind_param($stmt, 'iisi', $cantidad, $producto_id, $sesion_id, $usuario_id);
    } else {
        mysqli_stmt_bind_param($stmt, 'siii', $sesion_id, $usuario_id, $producto_id, $cantidad);
    }
    
    return mysqli_stmt_execute($stmt);
}

function obtenerCarrito($conexion) {
    $sesion_id = session_id();
    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;
    
    $sql = "SELECT ct.*, p.nombre, p.precio, p.imagen, p.stock, p.unidad 
            FROM carrito_temporal ct 
            JOIN productos p ON ct.producto_id = p.id 
            WHERE ct.sesion_id = ? OR ct.usuario_id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $sesion_id, $usuario_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function limpiarCarrito($conexion) {
    $sesion_id = session_id();
    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;
    
    $sql = "DELETE FROM carrito_temporal WHERE sesion_id = ? OR usuario_id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $sesion_id, $usuario_id);
    return mysqli_stmt_execute($stmt);
}

function migrarCarrito($conexion, $usuario_id) {
    $sesion_id = session_id();
    
    // Actualizar carrito temporal con el usuario_id
    $sql = "UPDATE carrito_temporal SET usuario_id = ? WHERE sesion_id = ? AND usuario_id IS NULL";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'is', $usuario_id, $sesion_id);
    mysqli_stmt_execute($stmt);
    
    // Eliminar duplicados (si el usuario ya tenía el producto en el carrito)
    $sql = "DELETE ct1 FROM carrito_temporal ct1
            INNER JOIN carrito_temporal ct2 
            WHERE ct1.id < ct2.id 
            AND ct1.producto_id = ct2.producto_id 
            AND ct1.usuario_id = ct2.usuario_id";
    mysqli_query($conexion, $sql);
}
function obtenerProductos($conexion, $categoria_id = null, $busqueda = null, $limit = null, $offset = 0) {
    $sql = "SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.activo = 1";
    
    $params = [];
    $types = '';
    
    if ($categoria_id) {
        $sql .= " AND p.categoria_id = ?";
        $params[] = $categoria_id;
        $types .= 'i';
    }
    
    if ($busqueda) {
        $sql .= " AND (p.nombre LIKE ? OR p.descripcion LIKE ?)";
        $params[] = "%$busqueda%";
        $params[] = "%$busqueda%";
        $types .= 'ss';
    }
    
    $sql .= " ORDER BY p.nombre";
    
    if ($limit) {
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
    }
    
    $stmt = mysqli_prepare($conexion, $sql);
    
    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function obtenerPedidosUsuario($conexion, $usuario_id) {
    $sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $usuario_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function obtenerTodosLosPedidos($conexion) {
    $sql = "SELECT p.*, u.nombre as usuario_nombre 
            FROM pedidos p 
            JOIN usuarios u ON p.usuario_id = u.id 
            ORDER BY p.fecha_pedido DESC";
    return mysqli_query($conexion, $sql);
}

function actualizarEstadoPedido($conexion, $pedido_id, $estado) {
    $sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $estado, $pedido_id);
    return mysqli_stmt_execute($stmt);
}

// Funciones específicas para administración
function obtenerEstadisticasDashboard($conexion) {
    $stats = [];
    
    // Total pedidos
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM pedidos");
    $stats['total_pedidos'] = mysqli_fetch_assoc($result)['total'];
    
    // Pedidos pendientes
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM pedidos WHERE estado = 'pendiente'");
    $stats['pedidos_pendientes'] = mysqli_fetch_assoc($result)['total'];
    
    // Total clientes
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'cliente'");
    $stats['total_clientes'] = mysqli_fetch_assoc($result)['total'];
    
    // Total productos
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM productos");
    $stats['total_productos'] = mysqli_fetch_assoc($result)['total'];
    
    // Ventas totales
    $result = mysqli_query($conexion, "SELECT SUM(total) as total FROM pedidos WHERE estado != 'cancelado'");
    $stats['ventas_totales'] = mysqli_fetch_assoc($result)['total'] ?? 0;
    
    return $stats;
}

function verificarAccesoAdministrador() {
    if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
        header("Location: ../auth/login.php");
        exit();
    }
}

function subirImagen($archivo, $directorio_destino, $nombre_actual = null) {
    if ($archivo['error'] !== 0) {
        return $nombre_actual; // Mantener el nombre actual si hay error
    }
    
    // Crear directorio si no existe
    if (!is_dir($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        return $nombre_actual; // Mantener el nombre actual si la extensión no es válida
    }
    
    // Generar nombre único
    $nuevo_nombre = uniqid() . '_' . time() . '.' . $file_extension;
    $archivo_destino = $directorio_destino . $nuevo_nombre;
    
    // Mover archivo
    if (move_uploaded_file($archivo['tmp_name'], $archivo_destino)) {
        // Eliminar archivo anterior si existe
        if ($nombre_actual && file_exists($directorio_destino . $nombre_actual)) {
            unlink($directorio_destino . $nombre_actual);
        }
        return $nuevo_nombre;
    }
    
    return $nombre_actual; // Mantener el nombre actual si falla la subida
}
?>