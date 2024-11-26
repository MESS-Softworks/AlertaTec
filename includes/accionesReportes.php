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
$correoD = obtenerCorreo($idReporte); 
$correoD = mysqli_fetch_assoc($correoD);
$correoD = $correoD['correoD'];
$correoD = decryptData($correoD);
$ruta_pdf = '../reportes/';
$ruta_evidencia = "../evidencias/".$idReporte."/";

$correo = new Correo();

switch($accion){
    case 'aceptar':
        echo "<p style='text-align: center;'>".aceptarReporte($idReporte)."</p>";
        
        if($correoD != ''){
            echo "<p style='text-align: center;'>Si hubo correo</p>";
            // $correo = new Correo();
            
            //     $destinatario = decryptData($correoD);
            //     $titulo = "Confirmación de Recepción de Tu reporte - $idReporte";
            
            //     $mensaje = "
            //         <html>
            //         <head>
            //             <title>Confirmación de Recepción de Tu Caso</title>
            //         </head>
            //         <body>
            //             <p>Estimado/a <strong>Denunciante</strong>,</p>
            //             <p>Gracias por confiar en nuestra plataforma para reportar tu caso. Este correo es para confirmarte que hemos recibido correctamente tu denuncia con el número de caso <strong>$idReporte</strong>.</p>
            //             <p>Queremos reiterarte que tu reporte será tratado con la máxima confidencialidad y que nuestro equipo se encargará de analizarlo de manera justa y profesional. En esta etapa inicial, nuestro equipo está revisando los detalles proporcionados para evaluar las medidas necesarias y los próximos pasos a seguir.</p>
            //             <h3>¿Qué puedes esperar ahora?</h3>
            //             <ul>
            //                 <li><strong>Evaluación inicial:</strong> Uno de nuestros especialistas revisará tu caso para determinar las acciones inmediatas necesarias, si aplican.</li>
            //                 <li><strong>Seguimiento:</strong> Te mantendremos informado/a sobre el progreso del caso según el canal de comunicación que elegiste al momento del registro.</li>
            //                 <li><strong>Apoyo:</strong> Si necesitas asistencia adicional, puedes comunicarte con nuestro equipo al correo <a href='mailto:alertateclag@gmail.com'>alertateclag@gmail.com</a>.</li>
            //             </ul>";
            
            //     if ($bandP == "Sí") {
            //         $mensaje .= "
            //             <h3>Apoyo Psicológico</h3>
            //             <p>Hemos registrado que solicitaste asistencia psicológica. Nuestro equipo especializado se pondrá en contacto contigo en las próximas [X horas/días] para coordinar el apoyo que necesites. Si tienes alguna preferencia respecto al horario o el medio de contacto, por favor háznoslo saber respondiendo a este correo.</p>";
            //     }
            
            //     $mensaje .= "
            //             <p>Estamos aquí para apoyarte en este proceso y asegurar que se respete tu integridad y bienestar en todo momento.</p>
            //             <p><strong>Habla, nosotros te respaldamos.</strong></p>
            //             <p>Atentamente,</p>
            //             <p><strong>Equipo AlertaTec<br>
            //             Instituto Tecnológico de la Laguna</strong><br>
            //             <a href='mailto:alertateclag@gmail.com'>alertateclag@gmail.com</a> | [Teléfono] | [Página Web, si aplica]</p>
            //         </body>
            //         </html>";
            
            //     $correo->enviarCorreo($destinatario, $titulo, $mensaje);
            //$correo->correoAceptar($idReporte, $correoD); /////////////////////////////////////////
        }else{
            echo "<p style='text-align: center;'>No hubo correo</p>";
        }

        $archivo = "Reporte_".$idReporte.".pdf";
        $ruta_completa_pdf = $ruta_pdf . $archivo;
        if (file_exists($ruta_completa_pdf)) {
            unlink($ruta_completa_pdf);
        }
    
        eliminarCarpeta($ruta_evidencia);
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
        
        
// echo "<!-- Tabla de denuncias -->";
// echo "<table class='report-table'>";
// echo "<thead>
//         <tr>
//             <th class='Cab'>ID</th>
//             <th class='Cab'>Denuncia</th>
//             <th class='Cab'>Prioridad</th>
//             <th class='Cab'>Evidencias</th>
//             <th class='Cab'>Acciones</th>
//         </tr>
//       </thead>
//       <tbody>";
// while ($reporte = mysqli_fetch_assoc($consulta)) {
//     $prioridad = $reporte['prioridad'];
//     echo "<tr>";
//     echo "<td>".$reporte['idReporte']."</td>";
//     echo "<td><a href='./includes/verReporte.php?carpeta=reportes&archivo=Reporte_".$reporte['idReporte'].".pdf' target='_blank'>Ver reporte</a></td>";
//     echo "<td>".Prioridad($prioridad)."</td>";
//     echo "<td>  
//             <div class='contBtn'>
//                 ".evidencia($reporte['idReporte'])."
//             </div>
//           </td>";
//     echo "<td>
//         <div class='contBtn'>
//             <button class='action-btn' onclick='confirmAction()'><img src='img/Aceptar.png' alt='' class='imgB'></button>
//             <button class='action-btn' onclick='deleteAction()'><img src='img/Rechazar.png' alt='' class='imgB'></button>
//         </div>
//     </td>";
//     echo "</tr>";            
// }

// echo "</tbody>
//     </table>";
?>