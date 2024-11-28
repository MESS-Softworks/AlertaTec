<?php
    session_start(); // Inicia la sesión

    // Si el usuario hace clic en el botón de cerrar sesión, se destruye la sesión
    if (isset($_POST['logout'])) {
        session_unset(); // Elimina todas las variables de sesión
        session_destroy(); // Destruye la sesión
        header("Location: login.php"); // Redirige al login
        exit();
    }

    // Verifica si el usuario ha iniciado sesión y si tiene el rol adecuado
    if (!isset($_SESSION['usuario']) || $_SESSION['role'] !== 'superadmin') {
        // Si no está logueado o el rol no es 'superadmin', redirige al login
        header("Location: Login.php"); // O redirige a login.html
        exit();
    }
    
    require './includes/conexion.php';
    require './includes/encryption.php';
    
    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $action = $_POST['action'] ?? ''; // Determina si es alta, baja o modificación
            $user_name = $_POST['user_name'] ?? ''; // Nombre de usuario
            $passw = $_POST['passw'] ?? ''; // Contraseña (si aplica)
            $passw = encryptData($passw);
            $role = $_POST['role'] ?? ''; // Rol: admin o superadmin
            // Determinar la tabla a usar según el rol
            $table = ($role === 'admin') ? 'ADMINISTRADOR' : 'SUPERADMINISTRADOR';
            $nameColumn = ($role === 'admin') ? 'nombreAdmin' : 'nombreSAdmin';
            $passwordColumn = ($role === 'admin') ? 'contraAdmin' : 'contraSAdmin';
            $rolePag = ($role === 'admin') ? 'Administrador' : 'SuperAdministrador';
    
            // Realizar operaciones CRUD según el caso
            switch ($action) {
                case 'alta':
                    $stmt = $conexion->prepare("INSERT INTO $table ($nameColumn, $passwordColumn) VALUES (:nombre, :contra)");
                    $stmt->bindParam(':nombre', $user_name);
                    $stmt->bindParam(':contra', $passw);
                    $stmt->execute();
                    if($role == 'admin'){
                        $MenConf = "Administrador añadido exitosamente.";
                    }else{
                        $MenConf = "SuperAdministrador añadido exitosamente.";
                    }
                    break;
                    
    
                case 'modificar':
                    $stmt = $conexion->prepare("UPDATE $table SET $passwordColumn = :contra WHERE $nameColumn = :nombre");
                    $stmt->bindParam(':nombre', $user_name);
                    $stmt->bindParam(':contra', $passw);
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        // Si se afectaron registros
                        if ($role == 'admin') {
                            $MenConf = "Administrador modificado exitosamente.";
                        } else {
                            $MenConf = "SuperAdministrador modificado exitosamente.";
                        }
                    } else {
                        if ($role == 'admin') {
                            $error = "No se encontró un Administrador con ese nombre para actualizar.";
                        } else {
                            $error = "No se encontró un SuperAdministrador con ese nombre para actualizar.";
                        }
                        
                        
                    }

                    break;
    
                case 'baja':
                    if ($role == 'superadmin') {
                        $query = $conexion->prepare("SELECT COUNT(*) FROM SUPERADMINISTRADOR");
                        $query->execute();
                        $count = $query->fetchColumn();
                    }else{
                        $count = 2;
                    }
                    
                        if ($count <= 1) {
                            // Si solo queda un registro, no permitir eliminar
                            $error = "No se puede eliminar el último SuperAdministrador.";
                        } else {
                    
                            $stmt = $conexion->prepare("DELETE FROM $table WHERE $nameColumn = :nombre");
                            $stmt->bindParam(':nombre', $user_name);
                            $stmt->execute();
                            if ($stmt->rowCount() > 0) {
                                // Si se afectaron registros
                                if ($role == 'admin') {
                                    $MenConf = "Administrador eliminado exitosamente.";
                                } else {
                                    $MenConf = "SuperAdministrador eliminado exitosamente.";
                                }
                            } else {
                                if ($role == 'admin') {
                                    $error = "No se encontró un Administrador con ese nombre para eliminar.";
                                } else {
                                    $error = "No se encontró un SuperAdministrador con ese nombre para eliminar.";
                                }
                            }
                        }
                    
                    
                    break;

                default:
                    $error = "Operación no válida.";
            }
        }else{
            $rolePag = 'Administrador';
        }
    } catch (PDOException $e) {
        // Manejo de errores
        switch ($e->getCode()) {
            case 23000:
                $error = "Error: Violación de restricción UNIQUE o clave foránea.";
                break;
            case 42000:
                $error = "Error: Sintaxis SQL inválida.";
                break;
            case 2002:
                $error = "Error: No se pudo conectar a la base de datos.";
                break;
            case 1049:
                $error = "Error: La base de datos especificada no existe.";
                break;
            default:
                $error = "Error inesperado: " . $e->getMessage();
        }
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/SuperAdmin.css">
    <title>SuperAdministrador</title>
    <link rel="icon" href="img/Huella3.png">
    <script src="JS/Admin.js"></script>
</head>
<body class="Fondo">
    <div class="Todo">
    <header>
        <div id="sidebar" class="sidebar">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
            <h2>Usuario</h2>
        <div class="botones">
            <button onclick="actualizarTabla('Administrador')" class="btnMenu">Administrador</button>
            <button onclick="actualizarTabla('SuperAdministrador')" class="btnMenu">SuperAdministrador</button>
        </div>

        <form method="POST">
            <button type="submit" name="logout" class="logout-btn">Cerrar sesión</button>
        </form>
    </div>
    
        <span id="abrir" class="openbtn" onclick="openNav()">☰</span>
    

        
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
    </header>

    <div>
        <h1 id="TT" class="tituloT">Administrador</h1>
        <?php if (isset($error)) echo "<p style='color:red; text-align: center;'>$error</p>"; ?>
        <?php if (isset($MenConf)) echo "<p style='color:green; text-align: center;;'>$MenConf</p>"; ?>
    </div>
<div class="segundo"> 
    <div class="seccion active" id="seccion1">
        <div class="Admin">
            <div class="formulario">
                <form method="POST" class="formD">  
                    <input type="hidden" name="action" value="alta">
                    <input type="hidden" name="role" value="admin">
                    <img src="img/Usuario.png" class="imagenU"><br>
                    <label for="user_name" class="etiqueta">Nombre de Usuario:</label>
                    <input type="text" id="correoE" name="user_name" required><br>
                    <label for="user_name" class="etiqueta">Contraseña:</label>
                    <input type="password" id="passw" name="passw" required><br>
                    <input type="submit" value="Alta" class="boton"><br>
                    <input type="reset" value="Reset" class="boton">
                </form>
            </div>
            <div class="formulario">
                <form method="POST" class="formD">  
                    <input type="hidden" name="action" value="baja">
                    <input type="hidden" name="role" value="admin">
                    <img src="img/Usuario.png" class="imagenU"><br>
                    <label for="user_name" class="etiqueta">Nombre de Usuario:</label>
                    <input type="text" id="correoE" name="user_name" required><br>
                    <input type="submit" value="Baja" class="boton"><br>
                    <input type="reset" value="Reset" class="boton">
                </form>
            </div>
            <div class="formulario">
                <form method="POST" class="formD">  
                    <input type="hidden" name="action" value="modificar">
                    <input type="hidden" name="role" value="admin">
                    <img src="img/Usuario.png" class="imagenU"><br>
                    <label for="user_name" class="etiqueta">Nombre de Usuario:</label>
                    <input type="text" id="correoE" name="user_name" required><br>
                    <label for="user_name" class="etiqueta">Contraseña:</label>
                    <input type="password" id="passw" name="passw" required><br>
                    <input type="submit" value="Modificar" class="boton"><br>
                    <input type="reset" value="Reset" class="boton">
                </form>
            </div>
        </div>
    </div>

    <div class="seccion" id="seccion2">
        <div class="Admin">
            <div class="formulario">
                <form method="POST" class="formD">  
                    <input type="hidden" name="action" value="alta">
                    <input type="hidden" name="role" value="superadmin">
                    <img src="img/Usuario.png" class="imagenU"><br>
                    <label for="user_name" class="etiqueta">Nombre de Usuario:</label>
                    <input type="text" id="correoE" name="user_name" required><br>
                    <label for="user_name" class="etiqueta">Contraseña:</label>
                    <input type="password" id="passw" name="passw" required><br>
                    <input type="submit" value="Alta" class="boton"><br>
                    <input type="reset" value="Reset" class="boton">
                </form>
            </div>
            <div class="formulario">
                <form method="POST" class="formD">  
                    <input type="hidden" name="action" value="baja">
                    <input type="hidden" name="role" value="superadmin">
                    <img src="img/Usuario.png" class="imagenU"><br>
                    <label for="user_name" class="etiqueta">Nombre de Usuario:</label>
                    <input type="text" id="correoE" name="user_name" required><br>
                    <input type="submit" value="Baja" class="boton"><br>
                    <input type="reset" value="Reset" class="boton">
                </form>
            </div>
            <div class="formulario">
                <form method="POST" class="formD">  
                    <input type="hidden" name="action" value="modificar">
                    <input type="hidden" name="role" value="superadmin">
                    <img src="img/Usuario.png" class="imagenU"><br>
                    <label for="user_name" class="etiqueta">Nombre de Usuario:</label>
                    <input type="text" id="correoE" name="user_name" required><br>
                    <label for="user_name" class="etiqueta">Contraseña:</label>
                    <input type="password" id="passw" name="passw" required><br>
                    <input type="submit" value="Modificar" class="boton"><br>
                    <input type="reset" value="Reset" class="boton">
                </form>
            </div>
        </div>
    </div>
    <div class="tabla" id="tabla"> 
            <!-- <table class="report-table">
                
                <thead>
                    <tr>
                        <th class="Cab">Nombre</th>
                        <th class="Cab">Contraseña</th>
                    </tr>
                </thead>
            
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        
                    </tr>
                </tbody>
            </table> -->
        </div>
</div>
</div>

<script>
    // Ejecuta actualizarTabla cuando la página termine de cargarse
    window.onload = actualizarTabla(<?php echo "'$rolePag'"?>);
</script>
</body>
</html>