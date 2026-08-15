<?php
// Conexión a la base de datos
$servername = "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') ?: ""; // no hardcodees credenciales: define DB_PASSWORD como variable de entorno local
$dbname = "flujoviajes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $usuario = $_POST['usuario'] ?? ''; 
    $contrasena = $_POST['contrasena'] ?? '';
    

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rango = $row['rango']; 
        $contrasena_db = $row['contrasena']; 

        if ($contrasena === $contrasena_db) {
            // Iniciar sesión
            session_start();
            $_SESSION['usuario'] = $usuario; 
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['rango'] = $row['rango']; 
            

            if ($rango === 'transporte') {
                header("Location: index.php"); 
            } elseif ($rango === 'admin') {
                header("Location: admin/adminindex.php"); 
            } else {
                $error = "Rango no reconocido.";
            }
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }

    $stmt->close();
}

$conn->close();
?>
