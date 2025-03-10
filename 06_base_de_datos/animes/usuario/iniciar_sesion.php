<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );  

        require ("../conexion.php");
    ?>
</head>
<body>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuario = $_POST["usuario"];
            $contrasena = $_POST["contrasena"];

            /* $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = $_conexion -> query($sql); */

            $sql = $_conexion -> prepare("SELECT * FROM usuarios WHERE usuario = ?");

            $sql -> bind_param("s", $usuario);

            $sql -> execute();

            $resultado = $sql -> get_result();

            $_conexion -> close();

            // num_rows indica si hay una fila con ese contenido, es decir, si existe el usuario
            if ($resultado -> num_rows == 0) {
                echo "<h2>El usuario $usuario no existe</h2>";
            } else {
                $datos_usuario = $resultado -> fetch_assoc();
                /**
                 * Podemos acceder a:
                 * 
                 * $datos_usuario["usuario"];
                 * $datos_usuario["contrasena"];
                 */
                $acceso_concedido = password_verify($contrasena, $datos_usuario["contrasena"]);
                // var_dump($acceso_concedido);
                if ($acceso_concedido) {
                    // todo guay
                    session_start();
                    // Una vez registrado el usuario, podemos usar su variable
                    $_SESSION["usuario"] = $usuario;
                    // $_COOKIe["loquesea"] = $loquesea;
                } else {
                    echo "<h2>La contraseña es incorrecta</h2>";
                }
            }
            header("location: ../index.php");
            exit;
        }
    ?>

    <div class="container">
        <h1>Inicio de sesión</h1>    
        
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input class="form-control" type="text" name="usuario">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input class="form-control" type="password" name="contrasena">
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Iniciar sesión">
                <h3>¿No tienes cuenta todavía?</h3>
                <a href="registro.php" class="btn btn-secondary">Registrarse</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>