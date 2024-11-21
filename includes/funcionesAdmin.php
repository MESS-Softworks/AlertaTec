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
                    AND tipoDenuncia NOT LIKE '%Hostigamiento laboral o académico%' ORDER BY prioridad DESC";
        } else {
            // Consulta para reportes que coincidan con el tipo de denuncia específico
            $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%$tipoDenuncia%' ORDER BY prioridad DESC";
        }

        // Realizar la consulta
        $reportes = mysqli_query($conexion, $sql);
        return $reportes;

    } catch (\Throwable $th) {
        var_dump($th);
    }
}

function obtener_admins($tipoAdmin){
    try {
        require 'database.php';
        
        if ($tipoAdmin === 'Administrador') {
            // Consulta para reportes que no coincidan con los tipos especificados
            $sql = "SELECT * FROM ADMINISTRADOR";
        } else {
            // Consulta para reportes que coincidan con el tipo de denuncia específico
            $sql = "SELECT * FROM SUPERADMINISTRADOR";
        }

        // Realizar la consulta
        $admins = mysqli_query($conexion, $sql);
        return $admins;

    } catch (\Throwable $th) {
        var_dump($th);
    }
}

function obtener_evidencias($idReporte){
    try {
        require 'database.php';
        
        // if ($tipoAdmin === 'Administrador') {
        //     // Consulta para reportes que no coincidan con los tipos especificados
        //     $sql = "SELECT * FROM ADMINISTRADOR";
        // } else {
        //     // Consulta para reportes que coincidan con el tipo de denuncia específico
        //     $sql = "SELECT * FROM SUPERADMINISTRADOR";
        // }

        $sql = "SELECT 
                    E.idE AS idE,
                    E.nombreE AS nombreE,
                    E.rutaArchivoE AS rutaArchivoE,
                    E.descripcionE AS descripcionE,
                    RE.idReporte AS idReporte
                FROM 
                    EVIDENCIAS E
                JOIN 
                    REPORTE_EVIDENCIAS RE ON E.idE = RE.idE
                WHERE 
                    RE.idReporte = '$idReporte';";

        // Realizar la consulta
        $evidencias = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($evidencias) > 0) {
            // Hay coincidencias
            return $evidencias;
        } else {
            // No hay coincidencias
            return null; // O algún otro valor para indicar que no hay resultados
        }

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