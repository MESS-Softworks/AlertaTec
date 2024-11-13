<?php
// session_start();

// // Variables de usuario para validar (esto debe reemplazarse por validación en la base de datos)
// $usuario_valido = "admin@ejemplo.com";
// $contraseña_valida = "contraseña123";

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $usuario = $_POST['correoE'];
//     $contraseña = $_POST['passw'];

//     if ($usuario == $usuario_valido && $contraseña == $contraseña_valida) {
//         $_SESSION['usuario'] = $usuario;
//         header("Location: form.php");
//         exit();
//     } else {
//         $error = "Usuario o contraseña incorrectos";
//     }
// }
?>

<?php
require './includes/conexion.php';
require './includes/encryption.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreAdmin = $_POST['correoE'];
    $contraseñaAdmin = $_POST['passw'];

    // Consultar en la base de datos si el usuario existe
    $stmt = $conexion->prepare("SELECT * FROM ADMINISTRADOR WHERE nombreAdmin = :nombreAdmin");
    $stmt->bindParam(':nombreAdmin', $nombreAdmin);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar que el usuario exista y que la contraseña sea correcta
    if ($admin && $admin['contraseñaAdmin'] === $contraseñaAdmin) {
        $_SESSION['usuario'] = $nombreAdmin;
        header("Location: Form.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Login.css">
    <title>Login Admin</title>
    <link rel="icon" href="img/Huella3.png">
</head>
<body>
    <div class="contenedor">
        <div class="row">
            <div class="logos">
                <img src="img/Huella3.png" class="imagen" alt="Logo principal">
                <div class="logos1">
                    <h1 class="texto">ALERTA</h1>
                    <h1 class="texto1">TEC</h1>
                    <h2 class="texto2">“Habla, nosotros te respaldamos”</h2>
                    <img src="img/HuellaSin.png" class="imagen2" alt="Logo secundario">
                </div>
            </div>
            <script>
                function regresar() {
                    window.location.href = "index.html";
                }
            </script>
            <div>
                <button onclick="regresar()" class="boton"> < </button>
            </div>
        </div>

        <div class="formulario">
            <form class="formD" method="post" action="LoginPrueba.php">
                <img src="img/Usuario.png" class="imagenU"><br>
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <label for="correoE" class="etiqueta">Administrador:</label>
                <input type="text" id="correoE" name="correoE" required><br>
                <label for="passw" class="etiqueta">Contraseña:</label>
                <input type="password" id="passw" name="passw" required><br>
                <input type="submit" value="Iniciar sesión" class="boton"><br>
                <input type="reset" value="Reset" class="boton">
            </form>
        </div>
    </div>
</body>
</html>
