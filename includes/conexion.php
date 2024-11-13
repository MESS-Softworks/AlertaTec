<?php

// Configuración de conexión a la base de datos
$host = "localhost"; // Cambia si tu servidor de base de datos es diferente
$dbname = "alertatec"; // Nombre de tu base de datos
$username = "root"; // Usuario de la base de datos
$password = ""; // Contraseña de la base de datos

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


?>
