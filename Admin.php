<?php
    require './includes/funcionesAdmin.php';

    $consulta = obtener_reportes_por_tipo('Agresión verbal');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <script src="JS/Admin.js"></script>
    <link rel="stylesheet" href="css/Admin.css">
    <link rel="icon" href="img/Huella3.png">
</head>
<body class="Fondo">

    <div id="sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <h2>Agresiones</h2>
    <div class="botones">
        <button onclick="cambiarTituloA('TA','Agresiones')" class="btnMenu">Todas</button>
        <button onclick="cambiarTituloA('TA','Agresiones verbales', 'TA')" class="btnMenu">Verbal</button>
        <button onclick="cambiarTituloA('TA','Agresiones físicas', 'TA')" class="btnMenu">Física</button>
        <button onclick="cambiarTituloA('TA','Agresiones sexuales', 'TA')" class="btnMenu">Sexual</button>
        <button onclick="cambiarTituloA('TA','Acoso', 'TA')" class="btnMenu">Acoso</button>
        <button onclick="cambiarTituloA('TA','Discriminación','TA')" class="btnMenu">Discriminación</button>
        <button onclick="cambiarTituloA('TA','Ciberacoso', 'TA')" class="btnMenu">Ciberacoso</button>
        <button onclick="cambiarTituloA('TA','Hostigamiento', 'TA')" class="btnMenu">Hostigamiento</button>
        <button onclick="cambiarTituloA('TA','Otros','TA')" class="btnMenu">Otros</button>
    </div>
        <button class="logout-btn">Cerrar Sesión</button>
    </div>

    <span class="openbtn" onclick="openNav()">☰</span>


    <div id="main">
        <div class="huella">
                <img src="img/Huella3.png" class="imagen">
            <div class="inf">
                <div>
                    <h1 class="texto">ALERTA</h1>
                    <h1 class="texto1">TEC</h1>
                    <h2 class="texto2">“Habla, nosotros te respaldamos”</h2>
                    <img src="img/HuellaSin.png" class="imagen2">
                </div>
            </div>
        </div>

        <div>
            <h1 id="TA" class="tituloT">Agresión</h1>
        </div>
        <!-- Tabla de denuncias -->
        <table class="report-table">
            
            <thead>
                <tr>
                    <th class="Cab">ID</th>
                    <th class="Cab">Denuncias</th>
                    <th class="Cab">Acciones</th>
                </tr>
            </thead>
        
            <tbody>
            <?php while($reportes = mysqli_fetch_assoc($consulta)){?>
                <tr>
                    <td><?php echo $reportes['idReporte']?></td>
                    <td><!-- Aca va lo del link hacia el PDF --></td>
                    <td>
                        <div class="contBtn">
                            <button class="action-btn" onclick="confirmAction()"><img src="img/Aceptar.png" alt="" class="imgB"></button>
                            <button class="action-btn" onclick="deleteAction()"><img src="img/Rechazar.png" alt="" class="imgB"></button>
                        </div>
                    </td>
                </tr>
            <?php   } ?>



                <tr>
                    <td><!-- Aca va lo de ID --> </td>
                    <td><!-- Aca va lo del link hacia el PDF --></td>
                    <td>
                        <div class="contBtn">
                            <button class="action-btn" onclick="confirmAction()"><img src="img/Aceptar.png" alt="" class="imgB"></button>
                            <button class="action-btn" onclick="deleteAction()"><img src="img/Rechazar.png" alt="" class="imgB"></button>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="contBtn">
                            <button class="action-btn" onclick="confirmAction()"><img src="img/Aceptar.png" alt="" class="imgB"></button>
                            <button class="action-btn" onclick="deleteAction()"><img src="img/Rechazar.png" alt="" class="imgB"></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="contBtn">
                            <button class="action-btn" onclick="confirmAction()"><img src="img/Aceptar.png" alt="" class="imgB"></button>
                            <button class="action-btn" onclick="deleteAction()"><img src="img/Rechazar.png" alt="" class="imgB"></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>