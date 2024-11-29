<?php
require 'funcionesAdmin.php';

// Obtener el parámetro enviado por AJAX
$tipoAdmin = isset($_GET['tipoAdmin']) ? $_GET['tipoAdmin'] : '';

// Llamar a la función para obtener los reportes
$consulta = obtener_admins($tipoAdmin);


//Generar el codigo html que se debe desplegar en la pantalla de SuperAdministrador.
echo "<!-- Tabla de Admins -->";
echo "<table class='report-table'>";
echo "<thead>
            <tr>
                <th class='Cab'>Id</th>
                <th class='Cab'>Nombre</th>
            </tr>
        </thead>";
echo "<tbody>";
if($tipoAdmin == 'Administrador'){
    while ($admin = mysqli_fetch_assoc($consulta)) {
        echo "<tr>";
        echo "<td>".$admin['idAdmin']."</td>";
        echo "<td>".$admin['nombreAdmin']."</td>";
        echo "</tr>";            
    }
}else {
    while ($admin = mysqli_fetch_assoc($consulta)) {
        echo "<tr>";
        echo "<td>".$admin['idSAdmin']."</td>";
        echo "<td>".$admin['nombreSAdmin']."</td>";
        echo "</tr>";            
    }
}
echo "</tbody>
    </table>";
?>