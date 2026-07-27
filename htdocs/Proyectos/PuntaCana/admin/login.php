<?php 
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
if (isset($_POST['ingresar'])) {

    //-- Conexión a la base de datos
    $host = "localhost";
    $bd = "dogfather";
    $user = "root";
    $pwd = "programacion";

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$bd", $user, $pwd);
        if (!$conexion) {
            echo "No se realizó la conexión...";
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
    //-- Fin de la conexión

    $usuario = $_POST['usuario'];
    $pass = $_POST['password'];
    $sql = $conexion->prepare("SELECT id, nombre, password FROM usuarios WHERE id = :usu && password = :pwd ");
    $sql->bindParam(':usu', $usuario);
    $sql->bindParam(':pwd', $pass);
    $sql->execute();
    $resultado = $sql->rowCount();

    if ($resultado > 0) {
        $usuario = $sql->fetch(PDO::FETCH_LAZY);

        $_SESSION['usuario'] = 'ok';
        $_SESSION['nickname'] = $usuario['id'];
        $_SESSION['nomuser'] = $usuario['nombre'];
        header('Location: index.php');
        exit();
    } else {
        $msg = "Error: Están atacando nuestra info, ¡fuera de aquí hacker!";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <title>Inicio de sesión</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container">
        <main>
            <div class="card">
                <h1 class="fa fa-align-center titulo card-header" aria-hidden="true">Inicio de sesión</h1>
                <div class="card-body">
                    <?php if (isset($msg)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <strong><?php echo $msg; ?></strong>
                    </div>
                    <?php } ?>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">ID</label>
                            <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Ingrese el usuario" />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password del user:</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese su contraseña" />
                        </div>
                        <div class="card-footer text-muted">
                            <button type="submit" name="ingresar" class="btn btn-primary btn_ingresar">Ingresar</button>
                        </div>
                    </form>
                    <a class="btn btn-warning btn_regresar" href="../index.php" role="button">Regresar</a>
                </div>
            </div>
        </main>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
