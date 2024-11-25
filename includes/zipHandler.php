<?php
// Ruta base de archivos y carpeta a incluir en el ZIP
$idReporte = isset($_GET['idReporte']) ? $_GET['idReporte'] : '';
$ruta_pdf= '../reportes/'; // Archivo PDF
$ruta_carpeta = "../evidencias/".$idReporte."/"; // Carpeta con carpetas de evidencias
$nombre_subcarpeta = 'Evidencias'; // Nombre de la subcarpeta en el ZIP
$archivo_zip = $idReporte.".zip"; // Ruta temporal para el ZIP

// Función recursiva para añadir una carpeta al ZIP como subcarpeta
function agregarCarpetaAlZip($carpeta, $zip, $ruta_base, $nombre_subcarpeta) {
    $archivos = scandir($carpeta); // Obtener archivos y carpetas en la ruta

    foreach ($archivos as $archivo) {
        if ($archivo === '.' || $archivo === '..') {
            continue; // Omitir "." y ".."
        }

        $ruta_completa = $carpeta . DIRECTORY_SEPARATOR . $archivo;
        $ruta_relativa = $nombre_subcarpeta . '/' . substr($ruta_completa, strlen($ruta_base)); // Ruta relativa con la subcarpeta

        if (is_dir($ruta_completa)) {
            // Si es una carpeta, añadirla y recorrerla recursivamente
            $zip->addEmptyDir($ruta_relativa);
            agregarCarpetaAlZip($ruta_completa, $zip, $ruta_base, $nombre_subcarpeta);
        } else {
            // Si es un archivo, añadirlo al ZIP
            $zip->addFile($ruta_completa, $ruta_relativa);
        }
    }
}

// Crear el archivo ZIP
$zip = new ZipArchive();

if (!is_dir('../reportes/') || !is_writable('../reportes/')) {
    die("La carpeta '../reportes/' no existe o no tiene permisos de escritura.");
}

$archivo_zip = realpath('../reportes') . "/Reporte_" . $idReporte . ".zip";

if ($archivo_zip === false) {
    die("Error al resolver la ruta absoluta del archivo ZIP.");
}


// Abre o crea el archivo ZIP
if ($zip->open($archivo_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    // Añadir el archivo PDF al ZIP
    if (isset($_GET['archivo'])) {
        $archivo = basename($_GET['archivo']); // Evita inyecciones de rutas
        $ruta_completa_pdf = $ruta_pdf . $archivo;
        // Verificar si el archivo existe
        if (file_exists($ruta_completa_pdf)) {
            $zip->addFile($ruta_completa_pdf, basename($ruta_completa_pdf));
        } else {
            echo "El archivo PDF no existe.";
        }
    } else {
        echo "No se especificó ningún archivo.";
    }

    // Añadir la carpeta completa al ZIP como subcarpeta
    if (is_dir($ruta_carpeta)) {
        $zip->addEmptyDir($nombre_subcarpeta); // Crear la subcarpeta en el ZIP
        agregarCarpetaAlZip($ruta_carpeta, $zip, $ruta_carpeta, $nombre_subcarpeta);
    } else {
        echo "La carpeta no existe.";
    }


    // Cierra el archivo ZIP
    $zip->close();

    // Descargar el archivo ZIP
    if (file_exists($archivo_zip)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename ($archivo_zip) . '"');
        header('Content-Length: ' . filesize($archivo_zip));
        readfile($archivo_zip);

        // Eliminar el archivo ZIP temporal después de descargarlo
        unlink($archivo_zip);
        exit;
    } else {
        echo "Error al crear el archivo ZIP: " . $zip->status;
        //echo "Hola";
        echo realpath($archivo_zip); // Asegúrate de que devuelve una ruta válida
    }
} else {
    echo "No se pudo crear el archivo ZIP.";
}






