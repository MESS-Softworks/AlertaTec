<?php
require 'funcionesAdmin.php';
require 'Correo.php';
require 'encryption.php';

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
        rmdir($carpeta);
    }
}

// Obtener los parámetros enviados por AJAX
$idReporte = isset($_GET['idReporte']) ? $_GET['idReporte'] : ''; //Id del reporte
$accion = isset($_GET['accion']) ? $_GET['accion'] : ''; //la accion que se hara con el reporte

$ruta_pdf = '../reportes/'; //Ruta hacia la carpeta donde se guardan los pdf con los reportes
$ruta_evidencia = "../evidencias/".$idReporte."/"; //Ruta hacia la carpeta con las evidencias del caso

$correo = new Correo();

switch($accion){
    case 'aceptar':  //Si la accion es aceptar
        //Obtenemos informacion sobre si el usuario desea obtener actualizaciones
        $consulta = obtenerCorreo($idReporte);  
        $consultaD = mysqli_fetch_assoc($consulta);
        $correoD = $consultaD['correoD'];
        $seguimiento = $consultaD['seguimiento'];

        if($seguimiento == 'Sí'){ //Si el usuario quiere recibir actualizaciones
            //Se realiza la preparacion y envio del correo de actualizacion. 
            $correo = new Correo();
            
                $destinatario = decryptData($correoD); //Se desencripta el correo
                $titulo = "Actualización sobre tu Reporte - $idReporte";
            
                $mensaje = "
                    <html>
                    <head>
                        <title>Actualización sobre tu Reporte</title>
                    </head>
                    <body>
                        <p>Estimado/a <strong>Denunciante</strong>,</p>
                        <p>Gracias por confiar en AlertaTec para reportar tu situación. Queremos informarte que tu reporte con el número <strong>$idReporte</strong> ha sido aceptado y está en la fase de investigación.</p>
                        <p>En esta fase se realiza la recopilación de pruebas, que puede incluir testimonios y cualquier otra evidencia relevante que respalde la denuncia y ayude a esclarecer los hechos.</p>
                        <h3>¿Qué puedes esperar ahora?</h3>
                        <p>Es posible que se te contacte en el futuro a ti y a los testigos para realizar entrevistas y para realizar el análisis de las pruebas presentadas. Por lo que te pedimos que estes atento al correo por cualquier posible contacto.</p>
                        <p>Estamos aquí para apoyarte en este proceso y asegurar que se respete tu integridad y bienestar en todo momento.</p>
                        <p><strong>Habla, nosotros te respaldamos.</strong></p>
                        <p>Atentamente,</p>
                        <p><strong>Equipo AlertaTec<br>
                        Instituto Tecnológico de la Laguna</strong><br>
                        <a href='mailto:alertateclag@gmail.com'>alertateclag@gmail.com</a> |  <a href='alertatec.infinityfreeapp.com'>alertatec.infinityfreeapp.com</a> </p>
                    </body>
                    </html>";
            
                $correo->enviarCorreo($destinatario, $titulo, $mensaje);
        }

        $archivo = "Reporte_".$idReporte.".pdf"; //Preparamos el nombre del archivo
        $ruta_completa_pdf = $ruta_pdf . $archivo; //Y la ruta completa del archivo
        if (file_exists($ruta_completa_pdf)) { //Si el archivo existe lo elimina
            unlink($ruta_completa_pdf);
        }
    
        eliminarCarpeta($ruta_evidencia); //Elimina carpeta y sus contenidos

        //Ejecuta el metodo para eliminar los reportes de la base de datos e imprime un mensaje de confirmacion.
        echo "<p style='text-align: center;'>".aceptarReporte($idReporte)."</p>";        
        break;
    case 'papelera':
        //Envia el reporte a la papelera e imprime mensaje de confirmacion.
        echo "<p style='text-align: center;'>".papeleraReporte($idReporte)."</p>";
        break;
    case 'restaurar':
        //Restaure el reporte e imprime un mensaje de confirmacion.
        echo "<p style='text-align: center;'>".restaurarReporte($idReporte)."</p>";
        break;
    case 'borrarDef':
        //Borra el reporte relacionado con el ID
        $archivo = "Reporte_".$idReporte.".pdf";
        $ruta_completa_pdf = $ruta_pdf . $archivo;
        if (file_exists($ruta_completa_pdf)) {
            unlink($ruta_completa_pdf);
        }
        
        //Elimina la carpeta de evidencias y su interior.
        eliminarCarpeta($ruta_evidencia);

        echo "<p style='text-align: center;'>".borrarDefReporte($idReporte)."</p>";
        break; 
    case 'limpiar':
        //Hace una consulta para obtener todos los reportes.
        $consulta = obtenerPapelera();
        
        //Repasa cada reporte y por cada uno...
        while($reporte = mysqli_fetch_assoc($consulta)){
            //Elimina el reporte asociado.
            $idReporte = $reporte['idReporte'];
            $archivo = "Reporte_".$idReporte.".pdf";
            $ruta_completa_pdf = $ruta_pdf . $archivo;
            if (file_exists($ruta_completa_pdf)) {
                unlink($ruta_completa_pdf);
            }
        
            //Y borra la carpeta de evidencias
            $ruta_evidencia = "../evidencias/".$idReporte."/";
            eliminarCarpeta($ruta_evidencia);
            //Y elimina todo registro del reporte de la base de datos.
            borrarDefReporte($idReporte);
        }

        //Imprime un mensaje confirmando que todo lo que estaba en la papelera ha sido eliminado.
        echo "<p style='text-align: center;'>Todos los reportes de la papelera han sido eliminados definitivamente</p>";
        break;
        }
?>