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
                    AND tipoDenuncia NOT LIKE '%Hostigamiento laboral o académico%' AND estadoDenuncia = 'Pendiente' ORDER BY prioridad DESC ";
        } else {
            // Consulta para reportes que coincidan con el tipo de denuncia específico
            $sql = "SELECT * FROM REPORTE WHERE tipoDenuncia LIKE '%$tipoDenuncia%' AND estadoDenuncia = 'Pendiente' ORDER BY prioridad DESC
                    ";
        }

        // Realizar la consulta
        $reportes = mysqli_query($conexion, $sql);
        return $reportes;

    } catch (\Throwable $th) {
        var_dump($th);
    }
}

function obtenerPapelera() {
    try {
        require 'database.php';
        
       
        $sql = "SELECT * FROM REPORTE WHERE estadoDenuncia = 'Papelera'";
        

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

function aceptarReporte($idReporte){
    try {
        require 'database.php';

        $conexion->begin_Transaction();
        
        $stmt1 = $conexion->prepare("   DELETE FROM EVIDENCIAS
                                        WHERE idE IN (
                                            SELECT idE
                                            FROM REPORTE_EVIDENCIAS
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt1->execute();
        $stmt2 = $conexion->prepare("   DELETE FROM TESTIGOS
                                        WHERE idT IN (
                                            SELECT idTestigo
                                            FROM REPORTE_TESTIGO
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt2->execute();
        $stmt3 = $conexion->prepare("   DELETE FROM AGRESORES
                                        WHERE idA IN (
                                            SELECT idAgresor
                                            FROM REPORTE_AGRESOR
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt3->execute();
        $stmt4 = $conexion->prepare("   DELETE FROM DENUNCIANTE_TESTIGO
                                        WHERE idD IN (
                                            SELECT idD
                                            FROM REPORTE
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt4->execute();
        $stmt5 = $conexion->prepare("   DELETE FROM DENUNCIANTE
                                        WHERE idD IN (
                                            SELECT idD
                                            FROM REPORTE
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt5->execute();
        
        $stmt6 = $conexion->prepare("DELETE FROM REPORTE WHERE idReporte = $idReporte");
        $stmt6->execute();

        // Confirmar transacción
        $conexion->commit();
        return "Reporte #".$idReporte." Aceptado con éxito";
        
    } catch (\Throwable $th) {
        $conexion->rollback();
        var_dump($th);
        return "Error: No se pudo aceptar el reporte";
    }
                    
}


function papeleraReporte($idReporte){
    try {
        require 'database.php';
        
        $stmt = $conexion->prepare("UPDATE REPORTE SET estadoDenuncia = 'Papelera' WHERE idReporte = $idReporte") ;
        // $stmt->bindParam('estado', $user_name);
        // $stmt->bindParam('idReporte', $passw);
        if ($stmt->execute()){
            
            return "Reporte #".$idReporte." enviado a la papelera";
        }else{
            return "Error: No se pudo enviar a la papelera el reporte";
        }
    } catch (\Throwable $th) {
        var_dump($th);
    }
                    
}

function restaurarReporte($idReporte){
    try {
        require 'database.php';
        
        $stmt = $conexion->prepare("UPDATE REPORTE SET estadoDenuncia = 'Pendiente' WHERE idReporte = $idReporte") ;
        // $stmt->bindParam('estado', $user_name);
        // $stmt->bindParam('idReporte', $passw);
        if ($stmt->execute()){
            return "Reporte #".$idReporte." restaurado.";
        }else{
            return "Error: No se pudo enviar a la papelera el reporte";
        }
    } catch (\Throwable $th) {
        var_dump($th);
    }
                    
}

function borrarDefReporte($idReporte){
    try {
        require 'database.php';

        $conexion->begin_transaction();

        $stmt1 = $conexion->prepare("   DELETE FROM EVIDENCIAS
                                        WHERE idE IN (
                                            SELECT idE
                                            FROM REPORTE_EVIDENCIAS
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt1->execute();
        $stmt2 = $conexion->prepare("   DELETE FROM TESTIGOS
                                        WHERE idT IN (
                                            SELECT idTestigo
                                            FROM REPORTE_TESTIGO
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt2->execute();
        $stmt3 = $conexion->prepare("   DELETE FROM AGRESORES
                                        WHERE idA IN (
                                            SELECT idAgresor
                                            FROM REPORTE_AGRESOR
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt3->execute();
        $stmt4 = $conexion->prepare("   DELETE FROM DENUNCIANTE_TESTIGO
                                        WHERE idD IN (
                                            SELECT idD
                                            FROM REPORTE
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt4->execute();
        $stmt5 = $conexion->prepare("   DELETE FROM DENUNCIANTE
                                        WHERE idD IN (
                                            SELECT idD
                                            FROM REPORTE
                                            WHERE idReporte = $idReporte
                                        );
                                        ");
        $stmt5->execute();
        
        $stmt6 = $conexion->prepare("DELETE FROM REPORTE WHERE idReporte = $idReporte");
        $stmt6->execute();

        // Confirmar transacción
        $conexion->commit();
        return "Reporte #".$idReporte." eliminado definitivamente";

    } catch (\Throwable $th) {
        $conexion->rollback();
        var_dump($th);
        return "Error: No se pudo eliminar.";
    }
}

function obtenerCorreo($idReporte){
    try {
        require 'database.php';

        $sql = "SELECT 
                    DENUNCIANTE.correoD AS correoD,
                    REPORTE.seguimiento AS seguimiento
                FROM 
                    DENUNCIANTE
                JOIN 
                    REPORTE ON DENUNCIANTE.idD = REPORTE.idD
                WHERE 
                    REPORTE.idReporte = $idReporte;";

        // Realizar la consulta
        $correo = mysqli_query($conexion, $sql);

    
        // Hay coincidencias
            return $correo;
        

    } catch (\Throwable $th) {
        var_dump($th);
    }
}



?>