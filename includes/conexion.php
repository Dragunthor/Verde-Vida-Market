<?php
try {
// Datos de Conexion
$servidor = "localhost";
$usuario = "Danny"; // Cambiar según tu configuración
$password = "123"; // Cambiar según tu configuración
$bd = "verdevida_market";

// Cadena de Conexion
$con = mysqli_connect($servidor, $usuario, $password, $bd);

} catch (mysqli_sql_exception $e) {
    // Si falla por base de datos inexistente
    if ($e->getCode() === 1049) {
        $con_temp = new mysqli($servidor, $usuario, $password);
        $con_temp->query("CREATE DATABASE IF NOT EXISTS $bd");
        $con = new mysqli($servidor, $usuario, $password, $bd);
        echo "<p>Base de datos '$bd' creada automáticamente.</p>";
    } else {
        throw $e; // Relanza otros errores
    }
}

// Configurar charset
mysqli_set_charset($con, "utf8");
?>