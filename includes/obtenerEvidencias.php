<?php
require 'funcionesAdmin.php';

// Obtener el parámetro enviado por AJAX
$idReporte = isset($_GET['idReporte']) ? $_GET['idReporte'] : '';
echo $idReporte;

if($idReporte == 'Recargar'){

}else{
// Llamar a la función para obtener los reportes
$consulta = obtener_evidencias($idReporte);

    //Genera el codigo html para desplegar la tabla con las evidencias de un determinado reporte
    echo "<!-- Tabla de denuncias -->";
    echo "<table class='report-table'>";
    echo "<thead>
            <tr>
                <th class='Cab'>Nombre</th>
                <th class='Cab'>Descripcion</th>
                <th class='Cab'>Visualizar</th>
            </tr>
        </thead>
        <tbody>";
    while ($evi= mysqli_fetch_assoc($consulta)) {
        echo "<tr>";
        echo "<td>".$evi['nombreE']."</td>";
        echo "<td>".$evi['descripcionE']."</td>";
        echo "<td>
                <div class='contBtn'>
                    <a href='./includes/verEvidencia.php?carpeta=evidencias&sub=".$evi['idReporte']."&archivo=".$evi['nombreE']."' target='_blank'><img src='img/vista.png' alt='' class='imgB'></a>
                </div>
            </td>";
                
        echo "</tr>";            
    }
    echo "</tbody>
        </table>";
}
?>