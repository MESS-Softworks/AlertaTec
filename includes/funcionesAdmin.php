<?php

function obtener_reportes(){
    try{
        //Importar las credenciales
        require 'database.php'; 

        //Consulta SQL 
        $sql = "SELECT idReporte FROM REPORTE;";

        //Realizar la consulta
        $reportes = mysqli_query($conexion, $sql);

        //Acceder a los resultados
        return $reportes;


    } catch(\Throwable $th){
        var_dump($th);
    }
}

function obtener_reportes_por_tipo($tipoDenuncia) {
    try {
        require 'database.php';
        
        if ($tipoDenuncia === 'otros') {
            // Consulta para reportes que no coincidan con los tipos especificados
            $sql = "SELECT * FROM REPORTE 
                    WHERE tipoDenuncia NOT LIKE '%Agresión verbal%' 
                    AND tipoDenuncia NOT LIKE '%Agresión física%' 
                    AND tipoDenuncia NOT LIKE '%Agresión sexual%' 
                    AND tipoDenuncia NOT LIKE '%Acoso%' 
                    AND tipoDenuncia NOT LIKE '%Discriminación%' 
                    AND tipoDenuncia NOT LIKE '%Ciberacoso%' 
                    AND tipoDenuncia NOT LIKE '%Hostigamiento laboral o académico%'";
        } else {
            // Consulta para reportes que coincidan con el tipo de denuncia específico
            $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%$tipoDenuncia%'";
        }

        // Realizar la consulta
        $reportes = mysqli_query($conexion, $sql);
        return $reportes;

    } catch (\Throwable $th) {
        var_dump($th);
    }
}


// function obtener_reportes_agresion_verbal() {
//     try {
//         require 'database.php';
//         $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%Agresión verbal%'";
//         $reportes = mysqli_query($conexion, $sql);
//         return $reportes;
//     } catch (\Throwable $th) {
//         var_dump($th);
//     }
// }

// function obtener_reportes_agresion_fisica() {
//     try {
//         require 'database.php';
//         $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%Agresión física%'";
//         $reportes = mysqli_query($conexion, $sql);
//         return $reportes;
//     } catch (\Throwable $th) {
//         var_dump($th);
//     }
// }

// function obtener_reportes_agresion_sexual() {
//     try {
//         require 'database.php';
//         $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%Agresión sexual%'";
//         $reportes = mysqli_query($conexion, $sql);
//         return $reportes;
//     } catch (\Throwable $th) {
//         var_dump($th);
//     }
// }

// function obtener_reportes_acoso() {
//     try {
//         require 'database.php';
//         $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%Acoso%'";
//         $reportes = mysqli_query($conexion, $sql);
//         return $reportes;
//     } catch (\Throwable $th) {
//         var_dump($th);
//     }
// }

// function obtener_reportes_discriminacion() {
//     try {
//         require 'database.php';
//         $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%Discriminación%'";
//         $reportes = mysqli_query($conexion, $sql);
//         return $reportes;
//     } catch (\Throwable $th) {
//         var_dump($th);
//     }
// }

// function obtener_reportes_ciberacoso() {
//     try {
//         require 'database.php';
//         $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%Ciberacoso%'";
//         $reportes = mysqli_query($conexion, $sql);
//         return $reportes;
//     } catch (\Throwable $th) {
//         var_dump($th);
//     }
// }

// function obtener_reportes_hostigamiento_laboral() {
//     try {
//         require 'database.php';
//         $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%Hostigamiento laboral o académico%'";
//         $reportes = mysqli_query($conexion, $sql);
//         return $reportes;
//     } catch (\Throwable $th) {
//         var_dump($th);
//     }
// }

// function obtener_reportes_otros() {
//     try {
//         require 'database.php';
//         $sql = "SELECT * FROM REPORTE 
//                 WHERE tipoDenuncia NOT LIKE '%Agresión verbal%' 
//                 AND tipoDenuncia NOT LIKE '%Agresión física%' 
//                 AND tipoDenuncia NOT LIKE '%Agresión sexual%' 
//                 AND tipoDenuncia NOT LIKE '%Acoso%' 
//                 AND tipoDenuncia NOT LIKE '%Discriminación%' 
//                 AND tipoDenuncia NOT LIKE '%Ciberacoso%' 
//                 AND tipoDenuncia NOT LIKE '%Hostigamiento laboral o académico%'";
//         $reportes = mysqli_query($conexion, $sql);
//         return $reportes;
//     } catch (\Throwable $th) {
//         var_dump($th);
//     }
// }




?>