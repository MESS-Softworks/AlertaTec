<?php
require 'funcionesAdmin.php';
require 'Correo.php';


// Obtener los parámetros enviados por AJAX
$idReporte = isset($_GET['idReporte']) ? $_GET['idReporte'] : '';
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
$correoD = obtenerCorreo($idReporte); 

switch($accion){
    case 'aceptar':
        echo "<p style='text-align: center;'>".aceptarReporte($idReporte)."</p>";
        if($correoD != NULL){
            //correoAceptar($idReporte, $correoD);
        }
        break;
    case 'papelera':
        echo "<p style='text-align: center;'>".papeleraReporte($idReporte)."</p>";
        break;
    case 'restaurar':
        echo "<p style='text-align: center;'>".restaurarReporte($idReporte)."</p>";
        break;
    case 'borrarDef':
        echo "<p style='text-align: center;'>".borrarDefReporte($idReporte)."</p>";
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