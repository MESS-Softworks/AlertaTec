<?php
// Ruta base de la carpeta oculta
$ruta = '../reportes/';

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

if ($accion == 'descargar') {
    // Acción para descargar un archivo
    if (isset($_GET['archivo'])) {
        $archivo = basename($_GET['archivo']); // Evita inyecciones de rutas
        $ruta_completa = $ruta . $archivo;
        // Verificar si el archivo existe
        if (file_exists($ruta_completa)) {
            // Encabezados para la descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($archivo) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($ruta_completa));

            // Leer y enviar el archivo al navegador
            readfile($ruta_completa);
            exit;
        } else {
            echo "El archivo no existe.";
        }
    } else {
        echo "No se especificó ningún archivo.";
    }
} elseif ($accion == 'eliminar') {
    // Acción para eliminar un archivo
    if (isset($_GET['archivo'])) {
        $archivo = basename($_GET['archivo']); // Evita inyecciones de rutas
        $ruta_completa = $ruta . $archivo;

        // Verificar si el archivo existe
        if (file_exists($ruta_completa)) {
            if (unlink($ruta_completa)) {
                echo "Archivo eliminado exitosamente.";
            } else {
                echo "No se pudo eliminar el archivo.";
            }
        } else {
            echo "El archivo no existe.";
        }
    } else {
        echo "No se especificó ningún archivo.";
    }
} else {
    echo "Acción no válida.";
}
