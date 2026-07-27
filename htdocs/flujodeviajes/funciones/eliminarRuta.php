<?php
include('../admin/bd.php'); // Conexión a la base de datos
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se ha pasado el ID de la ruta a eliminar
if (!isset($_GET['viaje'])) {
    header("Location: consultaRuta.php"); // Redirigir si no hay ID
    exit();
}

$ruta_id = $_GET['viaje'];

// Preparar la consulta para eliminar la ruta
$stmt = $conn->prepare("DELETE FROM rutas WHERE id = ? AND usuario = ?");
$stmt->bind_param("is", $ruta_id, $_SESSION['usuario']);

if ($stmt->execute()) {
    // Redirigir a la página de consulta con un mensaje de éxito
    header("Location: consultaRuta.php?eliminado=true");
    exit();
} else {
    // En caso de error, redirigir con un mensaje de error
    header("Location: consultaRuta.php?error_eliminar=true");
    exit();
}

?>
