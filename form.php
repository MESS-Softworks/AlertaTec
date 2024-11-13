<?php
require './includes/database.php';
require './includes/encryption.php';
require './includes/Correo.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar datos del formulario
    $tipoReporte = $_POST['tipoReporte'];
    $nombreD = $_POST['nombreD'] ?? null; // 
    $correoD = $_POST['correoD'] ?? null; // Es opcional si es anonimo
    if($tipoReporte == "Sí"){
        $correoD = $_POST['correoDA'] ?? null;
    }
    
    $numTelD = $_POST['numTelD'] ?? null; //
    $tipoDenunciante = $_POST['tipoDenunciante'];
    $relAfectado = $_POST['relacion_afectada'] ?? NULL;
    $relUniD = $_POST['relacion_universidad']; //Hay que modificar
    $departamentoD = NULL;
    $semestreD = NULL;
    if($relUniD == "Otro"){
        $relUniD = $_POST['relacion_otro'];
    }else{
        if($relUniD == "Alumno"){
            $departamentoD = $_POST['carrera'];
            $semestreD = $_POST['semestre'];
        }else if($relUniD == "Docente"){
            $departamentoD = $_POST['departamento_docente'];
        }else if($relUniD == "Personal administrativo"){
            $departamentoD = $_POST['departamento_admin'];
        }
    }

    $tipoD = $_POST['tipoD'];
    $fechaHecho = $_POST['fechaHecho'];
    $lugarHecho = $_POST['lugarHecho'];
    $detallesLugar = $_POST['detallesLugar'] ?? null; //Opcional 
    $descripcionR = $_POST['descripcionR'] ?? null; 
    $estadoDenuncia = "Pendiente"; // Inicialmente la denuncia se registra como pendiente
    //$prioridad = $_POST['prioridad']; // Podría depender de la gravedad
    $prioridad = 3;  //Le damos 1 por ahora unicamente para que sea llenada la base de datos.
    $evi = $_POST['evidencia'];
    $numTestigos = $_POST['num_testigos'];

    if($evi = "Sí"){
        $prioridad += 5;
    }
    if($tipoReporte == "No"){ //Reporte anonimo
        $prioridad += 4;
    }
    if($numTestigos != "0"){
        $prioridad += 3;
    }

    //Encriptamos la información sensible
    $nombreD = encryptData($nombreD);
    $correoD = encryptData($correoD);
    $numTelD = encryptData($numTelD);

    //Insertamos al denunciante
    $stmtDen = $conexion->prepare("INSERT INTO DENUNCIANTE (nombreD, correoD, numTelD, relUniD, tipoD, departamentoD, semestreD) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtDen->bind_param("sssssss", $nombreD, $correoD, $numTelD, $relUniD, $tipoDenunciante, $departamentoD, $semestreD);
    if ($stmtDen->execute()){
        $idDenunciante = $stmtDen->insert_id;
        if($tipoDenunciante == "Testigo"){
            $stmtDenTes = $conexion->prepare("INSERT INTO DENUNCIANTE_TESTIGO (idD, relAfectado) VALUES (?, ?)");
            $stmtDenTes->bind_param("is",$idDenunciante, $relAfectado);
            $stmtDenTes->execute();
        }
    }


    // Insertar datos del reporte en la tabla REPORTE
    $stmt = $conexion->prepare("INSERT INTO REPORTE (fechaReporte, idD, tipoReporte, tipoDenuncia, fechaHecho, lugarHecho, detallesLugar, descripcionR, estadoDenuncia, prioridad) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", $idDenunciante, $tipoReporte, $tipoD, $fechaHecho, $lugarHecho, $detallesLugar, $descripcionR, $estadoDenuncia, $prioridad);

    if ($stmt->execute()) {
        $idReporte = $stmt->insert_id; // Obtener el ID del reporte insertado
        echo "Reporte registrado con éxito. ID: " . $idReporte;

        // Primero, verifica si se seleccionó algún número de testigos
        if (isset($_POST['num_testigos']) && $_POST['num_testigos'] > 0) {
            // Recorre cada número de testigo y guarda los datos en la base de datos
            for ($i = 1; $i <= $numTestigos; $i++) {
                // Obtiene el rol del testigo y el nombre
                $rolT = $_POST["testigo_rol_{$i}"];
                $nombreT = $_POST["nombre_testigo_{$i}"];
                
                // Si el rol es "Otro", obtiene la especificación adicional
                if ($rolT == "Otro") {
                    $rolT = $_POST["testigo_otro_especificar_{$i}"];
                }

                // Inserta el testigo en la base de datos
                $stmtTestigo = $conexion->prepare("INSERT INTO TESTIGOS (nombreT, relUniT) VALUES (?, ?)");
                $stmtTestigo->bind_param("ss", $nombreT, $rolT);

                if ($stmtTestigo->execute()) {
                    $idTestigo = $stmtTestigo->insert_id;

                    // Inserta la relación entre el reporte y el testigo
                    $stmtReporteTestigo = $conexion->prepare("INSERT INTO REPORTE_TESTIGO (idReporte, idTestigo) VALUES (?, ?)");
                    $stmtReporteTestigo->bind_param("ii", $idReporte, $idTestigo);
                    $stmtReporteTestigo->execute();
                }
            }
        }

        // Primero, verifica si se seleccionó algún número de testigos
        if (isset($_POST['num_agresores']) && $_POST['num_agresores'] > 0) {
            $numAgresores = $_POST['num_agresores'];
            
            // Recorre cada número de testigo y guarda los datos en la base de datos
            for ($i = 1; $i <= $numAgresores; $i++) {
                // Obtiene el rol del testigo y el nombre
                $rolA = $_POST["agresor_rol_{$i}"];
                $nombreA = $_POST["nombre_agresor_{$i}"];
                
                // Si el rol es "Otro", obtiene la especificación adicional
                if ($rolA == "Otro") {
                    $rolA = $_POST["agresor_otro_especificar_{$i}"];
                }

                // Inserta el testigo en la base de datos
                $stmtAgresor = $conexion->prepare("INSERT INTO AGRESORES (nombreA, relUniA) VALUES (?, ?)");
                $stmtAgresor->bind_param("ss", $nombreA, $rolA);

                if ($stmtAgresor->execute()) {
                    $idAgresor = $stmtAgresor->insert_id;

                    // Inserta la relación entre el reporte y el testigo
                    $stmtReporteAgresor = $conexion->prepare("INSERT INTO REPORTE_AGRESOR (idReporte, idAgresor) VALUES (?, ?)");
                    $stmtReporteAgresor->bind_param("ii", $idReporte, $idAgresor);
                    $stmtReporteAgresor->execute();
                }
            }
        }

        // Insertar evidencias si se proporcionaron
        if (isset($_FILES['evidencias'])) {
            $rutaEvidencia = 'evidencias/'.$idReporte;
            if (!is_dir($rutaEvidencia)) {
                mkdir($rutaEvidencia, 0777, true); // 0777 da permisos completos, y true crea directorios recursivamente
            }

            foreach ($_FILES['evidencias']['tmp_name'] as $index => $tmpName) {
                $nombreEvidencia = $_FILES['evidencias']['name'][$index];
                $rutaEvidencia = 'evidencias/'.$idReporte.'/'.basename($nombreEvidencia);

                

                if (move_uploaded_file($tmpName, $rutaEvidencia)) {
                    //Insertar registro en la tabla evidencias
                    $stmtEvidencia = $conexion->prepare("INSERT INTO EVIDENCIAS (nombreE, rutaArchivoE, descripcionE) VALUES (?, ?, ?)");
                    $descripcionE = $_POST['descripcionE']; // Suponiendo que existe un array con las descripciones
                    $stmtEvidencia->bind_param("sss", $nombreEvidencia, $rutaEvidencia, $descripcionE);
                    if ($stmtEvidencia->execute()) {
                        $idEvidencia = $stmtEvidencia->insert_id;
                        $stmtReporteEvidencia = $conexion->prepare("INSERT INTO REPORTE_EVIDENCIAS (idReporte, idE) VALUES (?, ?)");
                        $stmtReporteEvidencia->bind_param("ii", $idReporte, $idEvidencia);
                        $stmtReporteEvidencia->execute();
                    }
                }
            }
        }else{
            echo "No entro";
        }

        $correo = new Correo();

        $destinatario = decryptData($correoD);
        $titulo = 'Gracias por tu denuncia!!';
        $mensaje = 'Tu reporte fue recibido, en este momento se encuentra en espera, te recordamos revisar el protocolo para conocer el proceso que llevará tu proceso, cuando avance en el proceso recibiras un nuevo correo avisandote a este mismo correo. Gracias Y recuerda que ¡Juntos somos mas fuertes!';

        $correo->enviarCorreo($destinatario, $titulo, $mensaje);

    } else {
        echo "Error al registrar el reporte: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Denuncia</title>
    <link rel="stylesheet" href="css/form.css">
</head>

<body>
    <h1>Formulario de Denuncia</h1>

    <!-- Formulario dividido por secciones -->
    <form id="formulario" method="POST" enctype="multipart/form-data">

        <!-- Sección 1: Tipo de denuncia -->
        <div class="seccion active" id="seccion1">
            <h2>Sección 1: Tipo de denuncia</h2>
            <label>1. ¿Desearías realizar una denuncia anónima?</label><br>

            <input class="radio" type="radio" id="anonimo_si" name="tipoReporte" value="Sí">
            <label for="anonimo_si">Sí</label><br><br>    

            <input class="radio" type="radio"  id="anonimo_no" name="tipoReporte" value="No">
            <label for="anonimo_no">No</label><br><br>

                <div id="no_anonimo_datos" style="display:none;">
                    <label for="nombreD">Nombre completo:</label>
                    <input type="text" id="nombreD" name="nombreD"><br>

                    <label for="correoD">Correo Electrónico (preferentemente institucional):</label>
                    <input type="email" id="correoD" name="correoD"><br>

                    <label for="numTelD">Número telefónico (opcional):</label>
                    <input type="tel" id="numTelD" name="numTelD"><br>

                    <p><strong>Aviso legal:</strong><br>
                    Al realizar una denuncia sin anonimidad autorizas que tu información sea compartida con las autoridades competentes dentro de la institución para que se tomen las medidas necesarias.<br><br></p>
                </div>

            <!-- Botón para pasar a la siguiente sección -->
            <button type="button" class="button" onclick="validarSeccion1()">Siguiente</button>
        </div>

        <!-- Sección 2: Datos del denunciante -->
        <div class="seccion" id="seccion2">
            <h2>Sección 2: Datos del denunciante</h2>
            <label>2. ¿Eres la persona afectada o un testigo?</label><br>
            <input class="radio" type="radio" id="afectada" name="tipoDenunciante" value="Persona afectada">
                <label for="afectada">Persona afectada</label><br><br>
                <input class="radio" type="radio" id="testigo" name="tipoDenunciante" value="Testigo">
                <label for="testigo">Testigo</label><br><br>

                <div id="testigo_relacion" style="display:none;">
                    <label for="relacion_afectada">¿Cuál es tu relación con la persona afectada?</label>
                    <input type="text" id="relacion_afectada" name="relacion_afectada"><br>
                </div>

            <label>3. ¿Cuál es tu relación con la universidad?</label><br>
            <input class="radio" type="radio" id="alumno" name="relacion_universidad" value="Alumno">
            <label for="alumno">Alumno</label><br><br>
                <div id="alumno_datos" style="display:none;">
                    <label for="carrera">¿A qué carrera perteneces?</label>
                    <input type="text" id="carrera" name="carrera"><br>

                    <label for="semestre">¿En qué semestre te encuentras actualmente?</label>
                    <select id="semestre" name="semestre">
                        <option value="1">1º Semestre</option>
                        <option value="2">2º Semestre</option>
                        <option value="3">3º Semestre</option>
                        <option value="4">4º Semestre</option>
                        <option value="5">5º Semestre</option>
                        <option value="6">6º Semestre</option>
                        <option value="7">7º Semestre</option>
                        <option value="8">8º Semestre</option>
                        <option value="9">9º Semestre</option>
                        <option value="10">10º Semestre o más</option>
                    </select><br>
                </div>

            <input class="radio" type="radio" id="docente" name="relacion_universidad" value="Docente">
            <label for="docente">Docente</label><br><br>
                <div id="docente_datos" style="display:none;">
                    <label for="departamento_docente">¿A qué departamento pertenece?</label>
                    <input type="text" id="departamento_docente" name="departamento_docente"><br>
                </div>

            <input class="radio" type="radio" id="administrativo" name="relacion_universidad" value="Personal administrativo">
            <label for="administrativo">Personal administrativo</label><br><br>
                <div id="administrativo_datos" style="display:none;">
                    <label for="departamento_admin">¿A qué departamento pertenece?</label>
                    <input type="text" id="departamento_admin" name="departamento_admin"><br>
                </div>

            <input class="radio" type="radio" id="otro" name="relacion_universidad" value="Otro">
            <label for="otro">Otro</label><br><br>
                <div id="otro_datos" style="display:none;">
                    <label for="relacion_otro">Especificar:</label>
                    <input type="text" id="relacion_otro" name="relacion_otro"><br>
                </div>

            <!-- Botón para pasar a la siguiente sección -->
            <button type="button" class="button" onclick="regresarSeccion('seccion1')">Atrás</button>
            <button type="button" class="button" onclick="validarSeccion2()">Siguiente</button>
        </div>

        <!-- Sección 3: Detalles de la denuncia -->
        <div class="seccion" id="seccion3">
            <h2>Sección 3: Detalles de la denuncia</h2>
            <label>4. ¿Qué tipo de agresión estás denunciando? (Puedes seleccionar más de una opción)</label><br>
            <input class="radio" type="checkbox" name="tipoDenuncia" value="Agresión verbal"> Agresión verbal (insultos, amenazas, comentarios ofensivos)<br><br>
            <input class="radio" type="checkbox" name="tipoDenuncia" value="Agresión física"> Agresión física (golpes, empujones, etc.)<br><br>
            <input class="radio" type="checkbox" name="tipoDenuncia" value="Agresión sexual"> Agresión sexual (violación, contacto sexual no deseado)<br><br>
            <input class="radio" type="checkbox" name="tipoDenuncia" value="Acoso"> Acoso (persecución, vigilancia)<br><br>
            <input class="radio" type="checkbox" name="tipoDenuncia" value="Discriminación"> Discriminación (género, raza, etc.)<br><br>
            <input class="radio" type="checkbox" name="tipoDenuncia" value="Ciberacoso"> Ciberacoso<br><br>
            <input class="radio" type="checkbox" name="tipoDenuncia" value="Hostigamiento laboral o académico"> Hostigamiento laboral o académico<br><br>
            <input class="radio" type="checkbox" id="Otro" value="Otro"> Otro (especificar):<br><br>
            <input type="text" name="agresion_otro" id="agresion_otro"><br>
            <input type="hidden" name="tipoD" id="tipoD" value= "">

            <label for="fechaHecho">5. ¿Cuándo ocurrió el incidente?</label>
            <input type="date" id="fechaHecho" name="fechaHecho"><br>

            <label>6. ¿Dónde ocurrió el incidente?</label><br>
            <input class="radio" type="radio" id="dentro" name="lugarHecho" value="Dentro de la institución">
            <label for="dentro">Dentro de la institución</label><br><br>
                <div id="lugar_dentro" style="display:none;">
                    <label for="detallesLugar">Describir el lugar (aula, edificio, áreas comunes, etc.):</label>
                    <input type="text" id="detallesLugar" name="detallesLugar"><br>
                </div>

            <input class="radio" type="radio" id="fuera" name="lugarHecho" value="Fuera de la institución">
            <label for="fuera">Fuera de la institución</label><br><br>

            <label for="num_agresores">7. Indica el número de agresores involucrados.</label>
            <select id="num_agresores" name="num_agresores" onchange="generarCamposAgresores()">
                <option value="0">ninguno</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6 o más</option>
            </select><br>
                <!-- Aquí se generarán las preguntas 7.1 y 7.2 -->
                <div id="campos_agresores"></div>


            <!-- Pregunta 8 -->
            <label for="descripcionR">8. Proporciona una descripción del hecho</label>
            <input type="text" id="descripcionR" name="descripcionR"><br>
            
            <!-- Botón para pasar a la siguiente sección -->
            <button type="button" class="button" onclick="regresarSeccion('seccion2')">Atrás</button>
            <button type="button" class="button" onclick="validarSeccion3()">Siguiente</button>
        </div>

        <!-- Sección 4: Evidencias -->
        <div class="seccion" id="seccion4">
            <h2>Sección 4: Evidencias</h2>
            <label>9. ¿Cuentas con algún tipo de evidencia?</label><br>
            <input class="radio" type="radio" id="evidencia_si" name="evidencia" value="Sí">
            <label for="evidencia_si">Sí</label><br><br>

            <input class="radio" type="radio" id="evidencia_no" name="evidencia" value="No">
            <label for="evidencia_no">No</label><br><br>
                <div id="adjunto_evidencias" style="display:none;">
                    <label for="evidencias">Adjunta las evidencias:</label>
                    <input type="file" id="evidencias" name="evidencias[]" multiple><br>
                    <label for="descripcionE">Describe la evidencia: </label>
                    <input type="text"name="descripcionE"><br>
                </div>
            

            <label for="num_testigos">10. Indica el número de testigos.</label>
            <select id="num_testigos" name="num_testigos" onchange="generarCamposTestigos()">
                <option value="0">Ninguno</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6 o más</option>
            </select><br>
                <!-- Aquí se generarán las preguntas 10.1 y 10.2 -->
                <div id="campos_testigos"></div>

            <!-- Botón para pasar a la siguiente sección -->
            <button type="button" class="button" onclick="regresarSeccion('seccion3')">Atrás</button>
            <button type="button" class="button" onclick="validarSeccion4()">Siguiente</button>
        </div>

        <!-- Sección 5: Finalizar denuncia -->
        <div class="seccion" id="seccion5">
            <h2>Sección 5: Finalizar denuncia</h2>
            <label>11. ¿Deseas recibir actualizaciones sobre el estado de la denuncia?</label><br>
            <input class="radio" type="radio" id="actualizaciones_correo" name="actualizaciones" value="Sí">
            <label for="actualizaciones_correo">Sí, deseo recibir actualizaciones sobre el estado de la denuncia.</label><br><br>
            <div id="anonimo_actualizacion" style="display:none;">
                <label for="correoD">Correo Electrónico:</label>
                <input type="email" id="correoDA" name="correoDA"><br>

                    <p><strong>Aviso legal:</strong><br>
                    A pesar de que la denuncia es anonima, requerimos de un correo para comunicarnos contigo cuando haya actualizaciones.<br><br></p>
                </div>
            <input class="radio" type="radio" id="no_actualizaciones" name="actualizaciones" value="No">
            <label for="no_actualizaciones">No, prefiero no recibir actualizaciones</label><br><br>

            <label>12. ¿Requieres apoyo o asistencia psicológica?</label><br>
            <input class="radio" type="radio" id="psicologico_si" name="psicologico" value="Sí">
            <label for="psicologico_si">Sí</label><br><br>
            <input class="radio" type="radio" id="psicologico_no" name="psicologico" value="No">
            <label for="psicologico_no">No</label><br><br>

            <!-- Botón para enviar el formulario -->
            <button type="button" class="button" onclick="regresarSeccion('seccion4')">Atrás</button>
            <button type="button" class="button" onclick="validarSeccion5()">Enviar denuncia</button>
        </div>
    </form>

    <script>
        // Función para mostrar la siguiente sección
        function mostrarSeccion(seccionId) {
            const secciones = document.querySelectorAll('.seccion');
            secciones.forEach(seccion => seccion.classList.remove('active'));
            document.getElementById(seccionId).classList.add('active');
        }

        //Función para regresarar a la sección anterior
        function regresarSeccion(seccionId) {
            const secciones = document.querySelectorAll('.seccion');
            secciones.forEach(seccion => seccion.classList.remove('active'));
            document.getElementById(seccionId).classList.add('active');
        }   

        // Función para generar las preguntas 7.1 y 7.2 según el número de agresores seleccionados
        function generarCamposAgresores() {
            const numAgresores = document.getElementById('num_agresores').value;
            const contenedor = document.getElementById('campos_agresores');
            contenedor.innerHTML = '';  

            for (let i = 1; i <= numAgresores; i++) {
                const campoAgresor = `
                    <h3>Detalles del agresor ${i}</h3>
                    <label for="agresor_rol_${i}">7.1 Indica el rol en la institución del agresor ${i}:</label><br>

                    <input class="radio" type="radio" id="agresor_alumno_${i}" name="agresor_rol_${i}" value="Alumno">
                    <label for="agresor_alumno_${i}">Alumno</label><br><br>

                    <input class="radio" type="radio" id="agresor_docente_${i}" name="agresor_rol_${i}" value="Docente">
                    <label for="agresor_docente_${i}">Docente</label><br><br>

                    <input class="radio" type="radio" id="agresor_admin_${i}" name="agresor_rol_${i}" value="Personal administrativo">
                    <label for="agresor_admin_${i}">Personal administrativo</label><br><br>

                    <input class="radio" type="radio" id="agresor_visitante_${i}" name="agresor_rol_${i}" value="Visitante">
                    <label for="agresor_visitante_${i}">Visitante</label><br><br>

                    <input class="radio" type="radio" id="agresor_otro_${i}" name="agresor_rol_${i}" value="Otro">
                    <label for="agresor_otro_${i}">Otro (especificar):</label><br><br>
                    <input type="text" id="agresor_otro_especificar_${i}" name="agresor_otro_especificar_${i}"><br>

                    <label for="nombre_agresor_${i}">7.2 Proporciona el nombre del agresor ${i}:</label><br>
                    <input type="text" id="nombre_agresor_${i}" name="nombre_agresor_${i}"><br>
                `;
                contenedor.innerHTML += campoAgresor;  
            }
        }

        // Función para generar las preguntas 10.1 y 10.2 según el número de testigos seleccionados
        function generarCamposTestigos() {
            const numTestigos = document.getElementById('num_testigos').value;
            const contenedor = document.getElementById('campos_testigos');
            contenedor.innerHTML = '';  // Limpiar el contenido antes de generar nuevas preguntas

            for (let i = 1; i <= numTestigos; i++) {
                const campoTestigo = `
                    <h3>Detalles del testigo ${i}</h3>
                    <label for="testigo_rol_${i}">10.1 Indica el rol en la institución del testigo ${i}:</label><br>

                    <input class="radio" type="radio" id="testigo_alumno_${i}" name="testigo_rol_${i}" value="Alumno">
                    <label for="testigo_alumno_${i}">Alumno</label><br><br>

                    <input class="radio" type="radio" id="testigo_docente_${i}" name="testigo_rol_${i}" value="Docente">
                    <label for="testigo_docente_${i}">Docente</label><br><br>

                    <input class="radio" type="radio" id="testigo_admin_${i}" name="testigo_rol_${i}" value="Personal administrativo">
                    <label for="testigo_admin_${i}">Personal administrativo</label><br><br>

                    <input class="radio" type="radio" id="testigo_otro_${i}" name="testigo_rol_${i}" value="Otro">
                    <label for="testigo_otro_${i}">Otro</label><br><br>
                    <input type="text" id="testigo_otro_especificar_${i}" name="testigo_otro_especificar_${i}"><br>

                    <label for="nombre_testigo_${i}">10.2 Proporciona el nombre del testigo ${i}:</label><br>
                    <input type="text" id="nombre_testigo_${i}" name="nombre_testigo_${i}"><br>
                `;
                contenedor.innerHTML += campoTestigo; 
            }
        }

        // Mostrar u ocultar campos de datos cuando se elige anonim o no
        document.getElementsByName('tipoReporte').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const noAnonimoDatos = document.getElementById('no_anonimo_datos');
                if (this.value === "No") {
                    noAnonimoDatos.style.display = "block";
                } else {
                    noAnonimoDatos.style.display = "none";
                }
            });
        });

        //Mostrar u ocultar campos si la persona afectada es testigo
        document.getElementsByName('tipoDenunciante').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const testigoRelacion = document.getElementById('testigo_relacion');
                if (this.value === "Testigo") {
                    testigoRelacion.style.display = "block";  // Mostrar el campo de relación si es "Testigo"
                } else {
                    testigoRelacion.style.display = "none";   // Ocultar el campo si no es "Testigo"
                }
            });
        });

        // Mostrar u ocultar campos según la relación con la universidad
        document.getElementsByName('relacion_universidad').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const alumnoDatos = document.getElementById('alumno_datos');
                const docenteDatos = document.getElementById('docente_datos');
                const adminDatos = document.getElementById('administrativo_datos');
                const otroDatos = document.getElementById('otro_datos');

                // Ocultar todos los campos al principio
                alumnoDatos.style.display = "none";
                docenteDatos.style.display = "none";
                adminDatos.style.display = "none";
                otroDatos.style.display = "none";

                // Mostrar el campo correspondiente según la selección
                if (this.value === "Alumno") {
                    alumnoDatos.style.display = "block";
                } else if (this.value === "Docente") {
                    docenteDatos.style.display = "block";
                } else if (this.value === "Personal administrativo") {
                    adminDatos.style.display = "block";
                } else if (this.value === "Otro") {
                    otroDatos.style.display = "block";
                }
            });
        });

        // Mostrar u ocultar campos según el lugar del incidente
        document.getElementsByName('lugarHecho').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const lugarDentro = document.getElementById('lugar_dentro');
                if (this.value === "Dentro de la institución") {
                    lugarDentro.style.display = "block";
                } else {
                    lugarDentro.style.display = "none";
                }
            });
        });

        // Mostrar u ocultar el campo para adjuntar evidencias según la respuesta
        document.getElementsByName('evidencia').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const adjuntoEvidencias = document.getElementById('adjunto_evidencias');
                if (this.value === "Sí") {
                    adjuntoEvidencias.style.display = "block";
                } else {
                    adjuntoEvidencias.style.display = "none";
                }
            });
        });

        // Mostrar u ocultar campos de datos cuando se elige anonim o no
        document.getElementsByName('actualizaciones').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const anonimoActualizacion = document.getElementById('anonimo_actualizacion');
                const anonimo = document.querySelector('input[name="tipoReporte"]:checked').value;
                console.log(anonimo);
                console.log("hola");
                console.log(this.value);
                if (this.value === "Sí" && anonimo === "Sí") {
                    anonimoActualizacion.style.display = "block";
                } else {
                    anonimoActualizacion.style.display = "none";
                }
            });
        });


        //Validación de respuestas Sección 1
        function validarSeccion1() {
            const tipoReporte = document.querySelector('input[name="tipoReporte"]:checked');
            const noAnonimoDatos = document.getElementById('no_anonimo_datos');
    
            if (!tipoReporte) {
                alert('Por favor selecciona si deseas realizar una denuncia anónima.');
                return;
            }

            if (tipoReporte.value === "No") {
                const nombre = document.getElementById('nombreD').value.trim();
                const correoD = document.getElementById('correoD').value.trim();
        
                if (!nombre || !correoD) {
                    alert('Por favor llena los campos de nombre y correo para continuar.');
                    return;
                }
            }

            mostrarSeccion('seccion2');
        }

        //Validación de respuestas Sección 2
        function validarSeccion2() {
            const rol = document.querySelector('input[name="tipoDenunciante"]:checked');
            const relacionUniversidad = document.querySelector('input[name="relacion_universidad"]:checked');

            if (!rol) {
                alert('Por favor selecciona si eres la persona afectada o un testigo.');
                return;
            }

            if (rol.value === "Testigo") {
                const relacionAfectada = document.getElementById('relacion_afectada').value.trim();
                if (!relacionAfectada) {
                    alert('Por favor proporciona tu relación con la persona afectada.');
                    return;
                }
            }

            if (!relacionUniversidad) {
                alert('Por favor selecciona tu relación con la universidad.');
                return;
            }

            if (relacionUniversidad.value === "Alumno") {
                const carrera = document.getElementById('carrera').value.trim();
                const semestre = document.getElementById('semestre').value;
                if (!carrera || !semestre) {
                    alert('Por favor ingresa tu carrera y selecciona el semestre.');
                    return;
                }
            } else if (relacionUniversidad.value === "Docente") {
                const departamentoDocente = document.getElementById('departamento_docente').value.trim();
                if (!departamentoDocente) {
                    alert('Por favor proporciona tu departamento.');
                    return;
                }
            } else if (relacionUniversidad.value === "Personal administrativo") {
                const departamentoAdmin = document.getElementById('departamento_admin').value.trim();
                if (!departamentoAdmin) {
                    alert('Por favor proporciona tu departamento.');
                    return;
                }
            } else if (relacionUniversidad.value === "Otro") {
                const relacionOtro = document.getElementById('relacion_otro').value.trim();
                if (!relacionOtro) {
                    alert('Por favor especifica tu relación con la universidad.');
                    return;
                }
            }

            mostrarSeccion('seccion3');
        }

        //Validación de respuestas Sección 3
        function validarSeccion3() {
    const tiposAgresion = document.querySelectorAll('input[name="tipoDenuncia"]:checked');
    const fechaIncidente = document.getElementById('fechaHecho').value;
    const lugarIncidente = document.querySelector('input[name="lugarHecho"]:checked');
    const numAgresores = document.getElementById('num_agresores').value;

    if (tiposAgresion.length === 0) {
        alert('Por favor selecciona al menos un tipo de agresión.');
        return;
    }

    if (!fechaIncidente) {
        alert('Por favor selecciona la fecha del incidente.');
        return;
    }

    if (!lugarIncidente) {
        alert('Por favor selecciona si el incidente ocurrió dentro o fuera de la institución.');
        return;
    }

    if (lugarIncidente.value === "Dentro de la institución") {
        const lugarDetalle = document.getElementById('detallesLugar').value.trim();
        if (!lugarDetalle) {
            alert('Por favor proporciona detalles del lugar donde ocurrió el incidente.');
            return;
        }
    }

    if (!numAgresores) {
        alert('Por favor selecciona el número de agresores involucrados.');
        return;
    }

    const num_agresores = document.getElementById('campos_agresores').children;
    for (let i = 0; i < numAgresores; i++) {
        const agresorRol = document.querySelector(`input[name="agresor_rol_${i+1}"]:checked`);
        const nombreAgresor = document.getElementById(`nombre_agresor_${i+1}`).value.trim();

        if (!agresorRol) {
            alert(`Por favor selecciona el rol del agresor ${i+1}.`);
            return;
        }

        if (!nombreAgresor) {
            alert(`Por favor proporciona el nombre del agresor ${i+1}.`);
            return;
        }
    }

    mostrarSeccion('seccion4');
}


        //Validar sección 4
        function validarSeccion4() {
            const evidencia = document.querySelector('input[name="evidencia"]:checked');
            const numTestigos = document.getElementById('num_testigos').value;

            if (!evidencia) {
                alert('Por favor selecciona si tienes evidencias.');
                return;
            }

            if (evidencia.value === "Sí") {
                const archivosEvidencia = document.getElementById('evidencias').files;
                if (archivosEvidencia.length === 0) {
                    alert('Por favor adjunta al menos una evidencia.');
                    return;
                }
            }

            if (!numTestigos) {
                alert('Por favor selecciona el número de testigos.');
                return;
            }

            const num_Testigos = document.getElementById('campos_testigos').children;
            for (let i = 0; i < numTestigos; i++) {
                const testigoRol = document.querySelector(`input[name="testigo_rol_${i+1}"]:checked`);
                const nombreTestigo = document.getElementById(`nombre_testigo_${i+1}`).value.trim();

                if (!testigoRol) {
                    alert(`Por favor selecciona el rol del testigo ${i+1}.`);
                    return;
                }

                if (!nombreTestigo) {
                    alert(`Por favor proporciona el nombre del testigo ${i+1}.`);
                    return;
                }
            }

            mostrarSeccion('seccion5');
        }

        //Validar sección 5
        function validarSeccion5() {
            const actualizaciones = document.querySelector('input[name="actualizaciones"]:checked');
            const apoyoPsicologico = document.querySelector('input[name="psicologico"]:checked');

            if (!actualizaciones) {
                alert('Por favor selecciona si deseas recibir actualizaciones sobre el estado de la denuncia.');
                return;
            }

            const block = document.getElementById('anonimo_actualizacion');
            if(block.style.display === "block"){
                const correo = document.getElementById('correoDA').value;
                if(!correo){
                    alert('Porfavor proporciona un correo.');
                    return;
                }
            }

            if (!apoyoPsicologico) {
                alert('Por favor selecciona si necesitas apoyo o asistencia psicológica.');
                return;
            }

            alert('Formulario enviado exitosamente.');
            document.getElementById('tipoD').value = ObtenerCheckbox();
            document.getElementById('formulario').submit();
        }

        //Obtener el valor de los checkbox
        function ObtenerCheckbox(){
            const tipoDenuncia =  document.querySelectorAll('input[name="tipoDenuncia"]:checked');
            const valores = Array.from(tipoDenuncia).map(checkbox => checkbox.value);
    
            // Incluye el valor del campo de texto "Otro" si está lleno
            const agresionOtro = document.getElementById('agresion_otro').value;
            let band = document.getElementById('Otro').checked;
            if (agresionOtro && band) {
                valores.push(agresionOtro);
            }

            return valores.join(', ');
            
        }
        
    </script>
</body>
</html>
