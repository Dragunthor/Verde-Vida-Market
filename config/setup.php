<?php
include '../includes/conexion.php';

// Crear tablas
$tablas = [
    "CREATE TABLE IF NOT EXISTS usuarios (
        id INT PRIMARY KEY AUTO_INCREMENT,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        nombre VARCHAR(100) NOT NULL,
        telefono VARCHAR(20),
        direccion TEXT,
        rol ENUM('admin', 'cliente') DEFAULT 'cliente',
        activo BOOLEAN DEFAULT TRUE,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS categorias (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(100) NOT NULL,
        descripcion TEXT,
        imagen VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS productos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        categoria_id INT,
        nombre VARCHAR(255) NOT NULL,
        descripcion TEXT,
        precio DECIMAL(10,2) NOT NULL,
        imagen VARCHAR(255),
        stock INT DEFAULT 0,
        unidad VARCHAR(50),
        origen VARCHAR(100),
        activo BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS pedidos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        usuario_id INT,
        estado ENUM('pendiente', 'confirmado', 'preparando', 'listo', 'entregado', 'cancelado') DEFAULT 'pendiente',
        total DECIMAL(10,2),
        metodo_pago ENUM('efectivo', 'transferencia', 'tarjeta') DEFAULT 'efectivo',
        notas TEXT,
        fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        fecha_entrega DATETIME,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS detalles_pedido (
        id INT PRIMARY KEY AUTO_INCREMENT,
        pedido_id INT,
        producto_id INT,
        cantidad INT NOT NULL,
        precio DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
        FOREIGN KEY (producto_id) REFERENCES productos(id)
    )",
    
    "CREATE TABLE IF NOT EXISTS carrito_temporal (
        id INT PRIMARY KEY AUTO_INCREMENT,
        sesion_id VARCHAR(255) NOT NULL,
        usuario_id INT NULL,
        producto_id INT,
        cantidad INT NOT NULL DEFAULT 1,
        fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
        FOREIGN KEY (producto_id) REFERENCES productos(id)
    )"
];

foreach ($tablas as $tabla) {
    if (!mysqli_query($con, $tabla)) {
        die("Error creando tabla: " . mysqli_error($con));
    }
}

// Insertar datos iniciales
$datos_iniciales = [
    "INSERT IGNORE INTO categorias (nombre, descripcion) VALUES 
    ('Frutas Orgánicas', 'Frutas frescas de cultivo local'),
    ('Verduras Ecológicas', 'Verduras de temporada sin pesticidas'),
    ('Lácteos Naturales', 'Productos lácteos artesanales')",
    
    "INSERT IGNORE INTO usuarios (email, password, nombre, telefono, direccion, rol) VALUES 
    ('admin@verdevida.com', '" . password_hash('Admin123', PASSWORD_DEFAULT) . "', 'Administrador', '987654321', 'Av. Principal 123', 'admin'),
    ('cliente1@gmail.com', '" . password_hash('Cliente123', PASSWORD_DEFAULT) . "', 'Danny', '999111222', 'Calle Falsa 123', 'cliente'),
    ('cliente2@gmail.com', '" . password_hash('Cliente123', PASSWORD_DEFAULT) . "', 'Carlos', '988777444', 'Av. Los Álamos 456', 'cliente'),
    ('cliente3@gmail.com', '" . password_hash('Cliente123', PASSWORD_DEFAULT) . "', 'Eric', '977555666', 'Jr. Las Flores 789', 'cliente')",
    
    // Productos de ejemplo
    "INSERT IGNORE INTO productos (categoria_id, nombre, descripcion, precio, stock, unidad, origen, imagen) VALUES 
    (1, 'Manzanas Orgánicas', 'Manzanas frescas cultivadas localmente sin pesticidas', 8.50, 50, 'Kg', 'Local', 'manzanas.png'),
    (1, 'Plátanos Ecológicos', 'Plátanos de cultivo ecológico y sostenible', 6.80, 30, 'Kg', 'Local', 'platanos.png'),
    (2, 'Lechuga', 'Lechuga fresca de cultivo orgánico', 4.20, 25, 'Unidad', 'Local', 'lechuga.png'),
    (2, 'Tomates', 'Tomates cultivados naturalmente', 7.90, 40, 'Kg', 'Local', 'tomates.png'),
    (3, 'Queso Fresco Artesanal', 'Queso fresco elaborado de forma tradicional', 12.50, 15, 'Kg', 'Local', 'queso.png'),
    (3, 'Yogurt Natural', 'Yogurt natural sin conservantes ni aditivos', 5.80, 20, 'Unidad', 'Local', 'yogurt.png')",];

foreach ($datos_iniciales as $dato) {
    if (!mysqli_query($con, $dato)) {
        echo "Error insertando dato: " . mysqli_error($con) . "<br>";
    }
}

// Crear directorios necesarios
$directorios = [
    '../uploads',
    '../uploads/productos',
    '../uploads/categorias',
    '../uploads/usuarios',
    '../assets/images'
];

foreach ($directorios as $directorio) {
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }
}

echo "<h2>Base de datos configurada correctamente!</h2>";
echo "<p>Usuario administrador creado:</p>";
echo "<ul>";
echo "<li><strong>Email:</strong> admin@verdevida.com</li>";
echo "<li><strong>Contraseña:</strong> Admin123</li>";
echo "</ul>";
echo "<p><a href='../index.php' class='btn btn-success'>Ir a la tienda</a></p>";

mysqli_close($con);
?>