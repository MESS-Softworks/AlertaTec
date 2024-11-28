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
$idReporte = isset($_GET['idReporte']) ? $_GET['idReporte'] : '';
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

$ruta_pdf = '../reportes/';
$ruta_evidencia = "../evidencias/".$idReporte."/";

$correo = new Correo();

switch($accion){
    case 'aceptar':
        $consulta = obtenerCorreo($idReporte); 
        $consultaD = mysqli_fetch_assoc($consulta);
        $correoD = $consultaD['correoD'];
        $seguimiento = $consultaD['seguimiento'];

        if($seguimiento == 'Sí'){
             $correo = new Correo();
            
                $destinatario = decryptData($correoD);
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

        $archivo = "Reporte_".$idReporte.".pdf";
        $ruta_completa_pdf = $ruta_pdf . $archivo;
        if (file_exists($ruta_completa_pdf)) {
            unlink($ruta_completa_pdf);
        }
    
        eliminarCarpeta($ruta_evidencia);
        echo "<p style='text-align: center;'>".aceptarReporte($idReporte)."</p>";
        break;
    case 'papelera':
        echo "<p style='text-align: center;'>".papeleraReporte($idReporte)."</p>";
        break;
    case 'restaurar':
        echo "<p style='text-align: center;'>".restaurarReporte($idReporte)."</p>";
        break;
    case 'borrarDef':
        
        $archivo = "Reporte_".$idReporte.".pdf";
        $ruta_completa_pdf = $ruta_pdf . $archivo;
        if (file_exists($ruta_completa_pdf)) {
            unlink($ruta_completa_pdf);
        }
    
        eliminarCarpeta($ruta_evidencia);

        echo "<p style='text-align: center;'>".borrarDefReporte($idReporte)."</p>";
        break; 
    case 'limpiar':
        $consulta = obtenerPapelera();
        while($reporte = mysqli_fetch_assoc($consulta)){
            $idReporte = $reporte['idReporte'];
            $archivo = "Reporte_".$idReporte.".pdf";
            $ruta_completa_pdf = $ruta_pdf . $archivo;
            if (file_exists($ruta_completa_pdf)) {
                unlink($ruta_completa_pdf);
            }
        
            $ruta_evidencia = "../evidencias/".$idReporte."/";
            eliminarCarpeta($ruta_evidencia);
            borrarDefReporte($idReporte);
        }

        echo "<p style='text-align: center;'>Todos los reportes de la papelera han sido eliminados definitivamente</p>";
        break;
        }
?>