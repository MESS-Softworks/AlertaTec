<?php
require 'funcionesAdmin.php';

function Prioridad($prioridad){
    if($prioridad >= 3 && $prioridad <= 4){
        return 'Baja';
    }else if($prioridad >= 5 && $prioridad <= 8){
        return 'Media';
    }else{
        return 'Alta';
    }
}

function evidencia($idReporte){
    $conEvi = obtener_Evidencias($idReporte);
    
    if($conEvi != NULL){
        return "<button class='action-btn' onclick='actualizarEvidencias(".$idReporte.")'><img src='img/Aceptar.png' alt='' class='imgB'></button>";
    }else{
        return "No hay evidencias que mostrar.";
    }
}

// Obtener el parámetro enviado por AJAX
$tipoDenuncia = isset($_GET['tipoDenuncia']) ? $_GET['tipoDenuncia'] : '';

// Llamar a la función para obtener los reportes
$consulta = obtener_reportes_por_tipo($tipoDenuncia);


echo "<!-- Tabla de denuncias -->";
echo "<table class='report-table'>";
echo "<thead>
        <tr>
            <th class='Cab'>ID</th>
            <th class='Cab'>Denuncia</th>
            <th class='Cab'>Prioridad</th>
            <th class='Cab'>Evidencias</th>
            <th class='Cab'>Acciones</th>
        </tr>
      </thead>
      <tbody>";
while ($reporte = mysqli_fetch_assoc($consulta)) {
    $prioridad = $reporte['prioridad'];
    echo "<tr>";
    echo "<td>".$reporte['idReporte']."</td>";
    echo "<td><a href='./includes/verReporte.php?carpeta=reportes&archivo=Reporte_".$reporte['idReporte'].".pdf' target='_blank'>Ver reporte</a></td>";
    echo "<td>".Prioridad($prioridad)."</td>";
    echo "<td>  
            <div class='contBtn'>
                ".evidencia($reporte['idReporte'])."
            </div>
          </td>";
    echo "<td>
        <div class='contBtn'>
            <button class='action-btn' onclick='confirmAction()'><img src='img/Aceptar.png' alt='' class='imgB'></button>
            <button class='action-btn' onclick='deleteAction()'><img src='img/Rechazar.png' alt='' class='imgB'></button>
        </div>
    </td>";
    echo "</tr>";            
}

echo "</tbody>
    </table>";
