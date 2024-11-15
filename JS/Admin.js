function openNav() {
    document.getElementById("sidebar").style.width = "250px";
}

function closeNav() {
    document.getElementById("sidebar").style.width = "0";
}

function confirmAction() {
    alert("Denuncia confirmada.");
}

function deleteAction() {
    alert("Denuncia eliminada.");
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




