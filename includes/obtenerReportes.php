<?php
require 'funcionesAdmin.php';

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
            <th class='Cab'>Acciones</th>
        </tr>
      </thead>
      <tbody>";
while ($reporte = mysqli_fetch_assoc($consulta)) {
    echo "<tr>";
    echo "<td>".$reporte['idReporte']."</td>";
    echo "<td><!-- Aca va lo del link hacia el PDF --></td>";
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
