<?php
function eliminarCarpeta($carpeta) {
    // Verifica si la carpeta existe
    if (is_dir($carpeta)) {
        // Listar los contenidos de la carpeta
        $elementos = scandir($carpeta);

        foreach ($elementos as $elemento) {
            // Ignorar las referencias a la carpeta actual y la carpeta superior ('.', '..')
            if ($elemento != '.' && $elemento != '..') {
                $ruta = $carpeta . '/' . $elemento;

                // Si es una carpeta, llamamos recursivamente
                if (is_dir($ruta)) {
                    eliminarCarpeta($ruta);
                } else {
                    // Si es un archivo, lo eliminamos
                    unlink($ruta);
                }
            }
        }

        // Una vez vacía, eliminamos la carpeta
        if (rmdir($carpeta)) {
            echo "Carpeta eliminada exitosamente.";
        } else {
            echo "No se pudo eliminar la carpeta.";
        }
    } else {
        //echo "La carpeta no existe.";
    }
}

// Ruta base de las carpetas
$idReporte = isset($_GET['idReporte']) ? $_GET['idReporte'] : '';
$ruta_pdf = '../reportes/';
$ruta_evidencia = "..evidencias/".$idReporte."/";

    // Acción para eliminar un archivo
    if (isset($_GET['archivo'])) {
        $archivo = basename($_GET['archivo']); // Evita inyecciones de rutas
        $ruta_completa_pdf = $ruta_pdf . $archivo;

        // Verificar si el archivo existe
        if (file_exists($ruta_completa_pdf)) {
            if (unlink($ruta_completa_pdf)) {
                echo "Archivo eliminado exitosamente.";
            } else {
                echo "No se pudo eliminar el archivo.";
            }
        } else {
            //echo "El archivo no existe.";
        }
    } else {
        echo "No se especificó ningún archivo.";
    }
    
    

    // Ejemplo de uso
    eliminarCarpeta($ruta_evidencia);
    