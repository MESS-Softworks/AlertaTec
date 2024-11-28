function openNav() {
    document.getElementById("sidebar").style.width = "250px";
    document.getElementById("abrir").classList.add("oculto"); // Oculta el icono de hamburguesa
}

function closeNav() {
    document.getElementById("sidebar").style.width = "0";   
    document.getElementById("abrir").classList.remove("oculto");
}

function accionesReportes(idReporte, accion){
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Actualiza la sección de la tabla con los resultados
            document.getElementById("Mensaje").innerHTML = this.responseText;
        }
    };
    // Enviar el tipo de denuncia como parámetro en la URL
    xhttp.open("GET", "./includes/accionesReportes.php?idReporte="+encodeURIComponent(idReporte)+"&accion="+ encodeURIComponent(accion), true);
    xhttp.send();
}

function aceptarReporte(idReporte, tipoDenuncia) {
    window.location.href = "./includes/zipHandler.php?idReporte="+idReporte+"&archivo=Reporte_"+idReporte+".pdf";
    accionesReportes(idReporte, 'aceptar');
    alert("Denuncia confirmada.");
    actualizarReportes(tipoDenuncia || '');
    actualizarEvidencias('Recargar');
}

function papeleraReporte(idReporte, tipoDenuncia) {
    accionesReportes(idReporte, 'papelera');
    alert("Denuncia enviada a papelera");
    actualizarReportes(tipoDenuncia || '');
    actualizarEvidencias('Recargar');
    
}

function restaurarReporte(idReporte){
    accionesReportes(idReporte, 'restaurar');
    alert("Denuncia Restaurada");
    mostrarPapelera();
    actualizarEvidencias('Recargar');
    
}

function borrarDefReporte(idReporte){
    //window.location.href = "./includes/borrarArchivos.php?idReporte="+idReporte+"&archivo=Reporte_"+idReporte+".pdf";
    accionesReportes(idReporte, 'borrarDef');
    alert("Denuncia Borrada Definitivmente");
    mostrarPapelera();
    actualizarEvidencias('Recargar'); 
}

function limpiarPapelera(){
    accionesReportes('papelera', 'limpiar');
    alert("La limpieza de la papelera ha sido completada con exito.");
    mostrarPapelera();
    actualizarEvidencias('Recargar'); 
}

function cambiarTituloA(idE,nuevoTitulo) {
    document.getElementById(idE).innerText = nuevoTitulo;
}

function actualizarReportes(tipoDenuncia) {
    cambiarTituloA('TA', tipoDenuncia || 'Todas');
    
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Actualiza la sección de la tabla con los resultados
            document.getElementById("tablaReportes").innerHTML = this.responseText;
        }
    };
    // Enviar el tipo de denuncia como parámetro en la URL
    xhttp.open("GET", "./includes/obtenerReportes.php?tipoDenuncia=" + encodeURIComponent(tipoDenuncia), true);
    xhttp.send();
}

function mostrarPapelera() {
    cambiarTituloA('TA', 'Papelera de reportes');
    
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Actualiza la sección de la tabla con los resultados
            document.getElementById("tablaReportes").innerHTML = this.responseText;
        }
    };
    // Enviar el tipo de denuncia como parámetro en la URL
    xhttp.open("GET", "./includes/obtenerPapelera.php", true);
    xhttp.send();
}

function actualizarTabla(tipoAdmin){
    cambiarTituloA('TT', tipoAdmin || 'Todas');
    
    if(tipoAdmin == 'Administrador'){
        mostrarSeccion('seccion1');
    }else{
        mostrarSeccion('seccion2');
    }
    
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Actualiza la sección de la tabla con los resultados
            document.getElementById("tabla").innerHTML = this.responseText;
        }
    };
    // Enviar el tipo de denuncia como parámetro en la URL
    xhttp.open("GET", "./includes/obtenerAdministradores.php?tipoAdmin=" + encodeURIComponent(tipoAdmin), true);
    xhttp.send();
}

function actualizarEvidencias(idReporte) {
    if(idReporte != 'Recargar'){
        cambiarTituloA('TE', `Reporte #${idReporte}`);
    }else{
        cambiarTituloA('TE', '');
    }
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Actualiza la sección de la tabla con los resultados
            document.getElementById("tablaEvidencias").innerHTML = this.responseText;
        }
    };
    // Enviar el tipo de denuncia como parámetro en la URL
    xhttp.open("GET", "./includes/obtenerEvidencias.php?idReporte=" + encodeURIComponent(idReporte), true);
    xhttp.send();
}

function mostrarSeccion(seccionId) {
    const secciones = document.querySelectorAll('.seccion');
    secciones.forEach(seccion => seccion.classList.remove('active'));
    document.getElementById(seccionId).classList.add('active');
}




