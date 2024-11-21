<?php
session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión y si tiene el rol adecuado
if (!isset($_SESSION['usuario']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirige al login si no está autorizado
    exit();
}

// Define las rutas de las carpetas protegidas
$carpetas_protegidas = [
    'evidencias' => '../evidencias/',
    'reportes' => '../reportes/',
];

// Verifica si la solicitud contiene la carpeta y el archivo
if (!isset($_GET['carpeta']) || !isset($_GET['archivo'])) {
    die("Solicitud no válida");
}

$carpeta = $_GET['carpeta'];
$archivo = $_GET['archivo'];


// Verifica que la carpeta solicitada sea válida
if (!array_key_exists($carpeta, $carpetas_protegidas)) {
    die("Acceso denegado a esta carpeta");
}

// Construye la ruta completa del archivo
$ruta_carpeta = $carpetas_protegidas[$carpeta];
$ruta_archivo = $ruta_carpeta . basename($archivo);

// Verifica si el archivo existe
if (!file_exists($ruta_archivo)) {
    die("Archivo no encontrado");
}

// Detecta el tipo MIME del archivo para visualizarlo correctamente
$tipo_mime = mime_content_type($ruta_archivo);
header('Content-Type: ' . $tipo_mime);
header('Content-Length: ' . filesize($ruta_archivo));

// Envía el archivo para su visualización en el navegador
readfile($ruta_archivo);
exit();



?>
