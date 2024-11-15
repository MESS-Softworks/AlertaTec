<?php
// session_start();

// // Variables de usuario para validar (esto debe reemplazarse por validación en la base de datos)
// $usuario_valido = "admin@ejemplo.com";
// $contraseña_valida = "contraseña123";

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $usuario = $_POST['user_name'];
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
    $nombreAdmin = $_POST['user_name'];
    $contraseñaAdmin = $_POST['passw'];
    $role = $_POST['role'];

    // Determinar la tabla y la página de redirección según el rol
    if ($role === "admin") {
        $table = "ADMINISTRADOR";
        $nameColumn = "nombreAdmin";
        $passwordColumn = "contraseñaAdmin";
        $redirectPage = "PruebaAdmin.php";
    } else {
        $table = "SUPERADMINISTRADOR";
        $nameColumn = "nombreSAdmin";
        $passwordColumn = "contraseñaSAdmin";
        $redirectPage = "SuperAdministrador.php";
    }

    // Consulta preparada para buscar el usuario en la tabla adecuada
    $stmt = $conexion->prepare("SELECT * FROM $table WHERE $nameColumn = :nombre");
    $stmt->bindParam(':nombre', $nombreAdmin);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar que el usuario exista y que la contraseña sea correcta
    if ($user && $user[$passwordColumn] === $contraseñaAdmin) {
        // Inicio de sesión exitoso, guardamos los datos en la sesión
        $_SESSION['usuario'] = $nombreAdmin;
        $_SESSION['role'] = $role;
        header("Location: $redirectPage");
        exit();
    } else {
        // Usuario o contraseña incorrectos
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
                <div class="inf">
                    <h1 class="texto">ALERTA</h1>
                    <h1 class="texto1">TEC</h1>
                    <h2 class="texto2">“Habla, nosotros te respaldamos”</h2>
                    <img src="img/HuellaSin.png " class="imagen2" alt="Logo secundario">
                </div>
            </div>
            <div>
                <button onclick="regresar()" class="boton"> < </button>
            </div>
        </div>

        <div class="formulario">
            <form class="formD" method="post" action="Login.php">  
                <img src="img/Usuario.png" class="imagenU"><br>
                <h2 class="TxI">Iniciar Sesion</h2>
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <input type="hidden" id="role" name="role" value="">
                <label for="user_name" class="etiqueta">Usuario:</label>
                <input type="text" id="user_name" name="user_name" required><br>
                <label for="passw" class="etiqueta">Contraseña:</label>
                <input type="password" id="passw" name="passw" required><br>
                <input type="submit" onclick="setRole('admin')" value="Administrador" class="boton"><br> 
                <input type="submit" onclick="setRole('superadmin')" value="SuperAdministrador" class="boton"><br> 
                <!-- <input type="reset" value="Reset" class="boton"> -->
            </form>
        </div>
    </div>

    <script>
        function regresar(){
            window.location.href = "index.html"
        }
        // Función para establecer el rol y enviar el formulario
        function setRole(role) {
            document.getElementById('role').value = role;
        }
    </script>
</body>
</html>