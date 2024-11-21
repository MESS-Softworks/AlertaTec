<?php
require 'funcionesAdmin.php';

// Obtener el parámetro enviado por AJAX
$idReporte = isset($_GET['idReporte']) ? $_GET['idReporte'] : '';

// Llamar a la función para obtener los reportes
$consulta = obtener_evidencias($idReporte);

//<th class='Cab'>Tipo de archivo</th> //Puede que lo ponga

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
    //$prioridad = $evi['prioridad'];
    echo "<tr>";
    echo "<td>".$evi['idE']."</td>";
    echo "<td>".$evi['descripcionE']."</td>";
    echo "<td>
            <div class='contBtn'>
                <a href='./includes/verEvidencia.php?carpeta=evidencias&sub=".$evi['idReporte']."&archivo=".$evi['nombreE']."' target='_blank'><img src='img/Aceptar.png' alt='' class='imgB'></a></td>
            </div>";
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