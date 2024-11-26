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
        return "<button class='action-btn' onclick='actualizarEvidencias(".$idReporte.")'><img src='img/carpeta.png' alt='' class='imgB'></button>";
    }else{
        return "No hay evidencias que mostrar.";
    }
}

// Obtener el parámetro enviado por AJAX
$tipoDenuncia = isset($_GET['tipoDenuncia']) ? $_GET['tipoDenuncia'] : '';

// Llamar a la función para obtener los reportes
$consulta = obtenerPapelera();


echo "<!-- Tabla de denuncias -->";

echo "<div class='borrarPapelera'>
        <a href='javascript:void(0);' onclick='limpiarPapelera()'>Limpiar Papelera</a>
        <a href='javascript:void(0);' onclick='limpiarPapelera()'><img src='img/borrar.png' alt='' class='imgB'></a>
      </div>";

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
            <button class='action-btn' onclick='restaurarReporte(".$reporte['idReporte'].")'><img src='img/restaurar.png' alt='' class='imgB'></button>
            <button class='action-btn' onclick='borrarDefReporte(".$reporte['idReporte'].")'><img src='img/borrar.png' alt='' class='imgB'></button>
        </div>
    </td>";
    echo "</tr>";            
}

echo "</tbody>
    </table>";
?>