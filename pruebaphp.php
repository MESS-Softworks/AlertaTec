<?php
require './includes/database.php';
require './includes/encryption.php';

$sql = "SELECT nombreD, correoD, numTelD FROM DENUNCIANTE WHERE idD = 2;";

$consulta = mysqli_query($conexion, $sql);

$denunciante = mysqli_fetch_assoc($consulta);

$nombreD = $denunciante ['nombreD'];
$correoD = $denunciante ['correoD'];
$numTelD = $denunciante ['numTelD'];


echo $nombreD;
echo $correoD;
echo $numTelD;

//Desencriptamos la información sensible
$nombreD = decryptData($nombreD);
$correoD = decryptData($correoD);
$numTelD = decryptData($numTelD);

echo "----------------------------------------------";
echo $nombreD;
echo $correoD;
echo $numTelD;
