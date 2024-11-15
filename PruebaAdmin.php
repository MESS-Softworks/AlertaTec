<?php
    require './includes/funcionesAdmin.php';

    $consulta = obtener_reportes();
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
        <button onclick="actualizarReportes('')" class="btnMenu">Todas</button>
        <button onclick="actualizarReportes('Agresión verbal')" class="btnMenu">Verbal</button>
        <button onclick="actualizarReportes('Agresión física')" class="btnMenu">Física</button>
        <button onclick="actualizarReportes('Agresión sexual')" class="btnMenu">Sexual</button>
        <button onclick="actualizarReportes('Acoso')" class="btnMenu">Acoso</button>
        <button onclick="actualizarReportes('Discriminación')" class="btnMenu">Discriminación</button>
        <button onclick="actualizarReportes('Ciberacoso')" class="btnMenu">Ciberacoso</button>
        <button onclick="actualizarReportes('Hostigamiento laboral o académico')" class="btnMenu">Hostigamiento</button>
        <button onclick="actualizarReportes('otros')" class="btnMenu">Otros</button>
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

        <div id="tablaReportes">
                    <!-- Aquí se cargará la tabla de reportes -->
        </div>
        
    </div>

    <script>
        // Ejecuta actualizarTabla cuando la página termine de cargarse
        window.onload = actualizarReportes('');
    </script>
</body>
</html>