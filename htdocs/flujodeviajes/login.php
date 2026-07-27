<?php
session_start();

// Incluir la conexión a la base de datos para el login
// ASEGÚRATE DE QUE 'admin/bd_login.php' CONTIENE LA CONEXIÓN CORRECTA A TU BASE DE DATOS
include('admin/bd_login.php');

$error_message = ''; // Variable para almacenar mensajes de error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    // Preparar la consulta SQL para evitar inyecciones SQL
    $sql = "SELECT id, usuario, contrasena FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $error_message = "Error al preparar la consulta: " . $conn->error;
    } else {
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {
            $fila = $resultado->fetch_assoc();
            // Verificar la contraseña (usar password_verify si las contraseñas están hasheadas)
            // Si la contraseña está en texto plano (NO RECOMENDADO PARA PRODUCCIÓN):
            if ($contrasena === $fila['contrasena']) { // CAMBIA ESTO POR password_verify($contrasena, $fila['contrasena']) SI USAS HASHES
                $_SESSION['usuario'] = $fila['usuario'];
                $_SESSION['id_usuario'] = $fila['id']; // Almacenar el ID del usuario si lo necesitas
                header("Location: index.php"); // Redirigir al panel de control
                exit();
            } else {
                $error_message = "Contraseña incorrecta.";
            }
        } else {
            $error_message = "Usuario no encontrado.";
        }
        $stmt->close();
    }
    $conn->close(); // Cerrar la conexión después de usarla
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MarTransport</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-ship login-icon"></i>
            <h2>Bienvenido a MarTransport</h2>
            <p>Por favor, inicia sesión para acceder al sistema.</p>
        </div>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="login-form">
            <div class="form-group">
                <label for="usuario"><i class="fas fa-user"></i> Usuario</label>
                <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Tu nombre de usuario" required autofocus>
            </div>
            <div class="form-group">
                <label for="contrasena"><i class="fas fa-lock"></i> Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Tu contraseña" required>
            </div>
            <button type="submit" class="btn btn-login-submit">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>