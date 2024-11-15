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


