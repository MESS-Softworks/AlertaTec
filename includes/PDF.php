<?php
require 'fpdf/fpdf.php';
require 'database.php';
require 'encryption.php';

function generarReportePDF($idReporte) {
global $conexion;

$strquery = "SELECT 
            REPORTE.idReporte, 

            REPORTE.tipoReporte,
            DENUNCIANTE.nombreD,
            DENUNCIANTE.correoD,
            DENUNCIANTE.numTelD,
            DENUNCIANTE.relUniD,
            DENUNCIANTE.tipoD,

            DENUNCIANTE_TESTIGO.relAfectado,

            DENUNCIANTE.departamentoD,
            DENUNCIANTE.semestreD,

            REPORTE.fechaReporte, 
            REPORTE.tipoDenuncia,
            REPORTE.fechaHecho, 
            REPORTE.lugarHecho,
            REPORTE.detallesLugar, 
            REPORTE.descripcionR,

            GROUP_CONCAT(DISTINCT IFNULL(TESTIGOS.nombreT, 'Sin Testigo') SEPARATOR ', ') AS nombresTestigos,
            GROUP_CONCAT(DISTINCT IFNULL(TESTIGOS.relUniT, 'Sin Relación') SEPARATOR ', ') AS relacionesTestigos,
            GROUP_CONCAT(DISTINCT IFNULL(AGRESORES.nombreA, 'Sin Agresor') SEPARATOR ', ') AS nombresAgresores,
            GROUP_CONCAT(DISTINCT IFNULL(AGRESORES.relUniA, 'Sin Relación') SEPARATOR ', ') AS relacionesAgresores

            FROM REPORTE
            INNER JOIN DENUNCIANTE ON REPORTE.idD = DENUNCIANTE.idD
            LEFT JOIN  DENUNCIANTE_TESTIGO ON DENUNCIANTE.idD = DENUNCIANTE_TESTIGO.idD
            LEFT JOIN REPORTE_TESTIGO ON REPORTE.idReporte = REPORTE_TESTIGO.idReporte
            LEFT JOIN TESTIGOS ON REPORTE_TESTIGO.idTestigo = TESTIGOS.idT
            LEFT JOIN REPORTE_AGRESOR  ON REPORTE.idReporte = REPORTE_AGRESOR.idReporte
            LEFT JOIN AGRESORES ON REPORTE_AGRESOR.idAgresor = AGRESORES.idA
            WHERE REPORTE.idReporte = ?
            GROUP BY REPORTE.idReporte";
            
$stmt = $conexion->prepare($strquery);
if (!$stmt) {
    die(utf8_decode("Error en la preparación de la consulta: " . $conexion->error));
}

$stmt->bind_param("i", $idReporte);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc(); 

if (!$data) {
    die(utf8_decode("No existe ese reporte."));
}


class PDF extends FPDF
{
function Header() {
    $this->SetTextColor(16,71,90);
    $this->SetFont('Times', 'B', 30);
    $this->setXY(60, 15);
    $this->Cell(100, 30, 'Reporte de Denuncia', 0, 0, 'C', 0);
    $this->Image('./img/Huella3.png', 165, 10, 35); 
    $this->Ln(h: 40);
}

function Footer() {
    $this->SetY(-15);

    $this->SetFont('Times', 'B', 11);
    $this->Cell(170, 10, '', 0, 0, 'C', 0);
    $this->Cell(25, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
}
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);


/******************  INFORMACIÓN DEL REPORTE  *****************************/
$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0, 10, 'Folio # ' . $data['idReporte'], 1, 1, 'C',1);

$pdf->SetFillColor(22,153,223);
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(90, 10, 'Fecha del Reporte', 1, 0, 'C',1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(0, 10, $data['fechaReporte'], 1, 'C');

$pdf->SetFillColor(22,153,223);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 10, 'Tipo de Denuncia', 1, 1, 'C',1);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->MultiCell(0, 10, utf8_decode($data['tipoDenuncia']), 1, 'C');

$pdf->Ln(h: 15);
/**************************************************************************/



/******************  INFORMACIÓN DEL DENUNCIANTE  *****************************/
$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Información del Denunciante'), 1, 1,'C',1);
$pdf->SetFont('Times', '', 12);

if ($data['tipoReporte'] != 'Sí') { 
    
    $pdf->SetFillColor(22,153,223);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(0, 10, 'Nombre', 1, 1, 'C',1);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0,0,b: 0);
    $pdf->MultiCell(0, 10, utf8_decode(decryptData($data['nombreD'])), 1, 'C');

    $pdf->SetFillColor(22,153,223);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(0, 10, 'Correo', 1, 1, 'C',1);
    $pdf->SetTextColor(0,0,b: 0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->MultiCell(0, 10, utf8_decode(decryptData($data['correoD'])), 1, 'C');

    $pdf->SetFillColor(22,153,223);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(90, 10, utf8_decode('Teléfono'), 1, 0, 'C',1);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0,0,b: 0);
    $pdf->MultiCell(0, 10, utf8_decode(decryptData($data['numTelD'])), 1, 'C');
    
}
$pdf->SetFillColor(22,153,223);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(90, 10, utf8_decode('Relación con la Universidad'), 1, 0, 'C',1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0,0,b: 0);
$pdf->MultiCell(0, 10, utf8_decode($data['relUniD']), 1, 'C');

$pdf->SetFillColor(22,153,223);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(90, 10, 'Tipo de Denunciante', 1, 0, 'C',1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0,0,b: 0);
$pdf->MultiCell(0, 10, utf8_decode($data['tipoD']), 1, 'C');

if ($data['tipoD'] == 'Testigo') {

    $pdf->SetFillColor(22,153,223);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('Relación con la Persona Afectada'), 1, 1, 'C', 1);
    $pdf->SetTextColor(0,0,b: 0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->MultiCell(0, 10, utf8_decode($data['relAfectado']), 1, 'C');

}

$pdf->SetFillColor(22,153,223);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 10, 'Departamento', 1, 1, 'C',1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0,0,b: 0);
$pdf->MultiCell(0, 10, utf8_decode($data['departamentoD']), 1, 'C');

if ($data['relUniD'] == 'Alumno') {

    $pdf->SetFillColor(22,153,223);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(90, 10, 'Semestre', 1, 0, 'C', 1);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0,0,b: 0);
    $pdf->MultiCell(0, 10, utf8_decode($data['semestreD']), 1, 'C');

}
/**************************************************************************/



/******************  INFORMACIÓN DEL HECHO  *****************************/
$pdf->AddPage();
$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 16);

$pdf->Cell(0, 10, utf8_decode('Información del Hecho'), 1, 1, 'C',1);
$pdf->SetFont('Times', 'B', 14);
$pdf->SetFillColor(22,153,223);
$pdf->Cell(90, 10, 'Fecha del Hecho', 1, 0, 'C',1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0,0,b: 0);
$pdf->MultiCell(0, 10, $data['fechaHecho'], 1, 'C');

if ($data['lugarHecho'] == 'Dentro de la institución') {
    $pdf->SetFillColor(22,153,223);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(90, 10, 'Lugar del Hecho', 1, 0, 'C',1);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0,0,b: 0);
    $pdf->MultiCell(0, 10, utf8_decode($data['lugarHecho']), 1, 'C');

    $pdf->SetFillColor(22,153,223);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(0, 10, 'Detalles del Lugar', 1, 1, 'C',1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(0,0,b: 0);
    $pdf->MultiCell(0, 10, utf8_decode($data['detallesLugar']), 1, 'L');

} else {

    $pdf->SetFillColor(22,153,223);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(90, 10, 'Lugar del Hecho', 1, 0, 'C',1);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0,0,b: 0);
    $pdf->MultiCell(0, 10, utf8_decode($data['lugarHecho']), 1, 'C');

}
$pdf->SetFillColor(22,153,223);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Descripción'), '1', 1, 'C',1);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0,g: 0,b: 0);
$pdf->MultiCell(0, 10, utf8_decode($data['descripcionR']), '1','L');

/**************************************************************************/

$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Testigos'), 1, 1,'C',1);
$pdf->SetFont('Times', '', 12);

if (!empty($data['nombresTestigos'])) {
    $nombresTestigos = array_map('trim', explode(",", $data['nombresTestigos']));

    foreach ($nombresTestigos as $index => $nombre) {
        $pdf->SetFillColor(22, 153, 223);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Cell(90, 10, 'Nombre del Testigo', 1, 0, 'C', 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell(0, 10, utf8_decode($nombre), 1, 'C');
        
    }
} else {
    $pdf->Cell(0, 10, 'No se reportaron testigos.', 1, 1, 'C');
}


$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Agresores'), 1, 1,'C',1);
$pdf->SetFont('Times', '', 12);
if (!empty($data['nombresAgresores'])) {
    $nombresAgresores = array_map('trim', explode(",", $data['nombresAgresores']));

    foreach ($nombresAgresores as $index => $nombre) {
        $pdf->SetFillColor(22, 153, 223);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Times', 'B', 14);
        $pdf->Cell(90, 10, 'Nombre del Agresor', 1, 0, 'C', 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell(0, 10, utf8_decode($nombre), 1, 'C');

    }
} else {
    $pdf->Cell(0, 10, 'No se reportaron agresores.', 1, 1, 'C');
}



$pdf->Output('F', "./reportes/Reporte_$idReporte.pdf");

}

?>