<?php
include('../admin/bd.php'); // Conexión a la base de datos
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se ha pasado el ID de la ruta a editar
if (!isset($_GET['viaje'])) {
    header("Location: consultaRuta.php"); // Redirigir si no hay ID
    exit();
}

$ruta_id = $_GET['viaje'];

// Obtener la información de la ruta para prellenar el formulario
$stmt = $conn->prepare("SELECT id, origen, destino, fecha_origen, fecha_destino, numero_barco, tipo_embarcacion FROM rutas WHERE id = ? AND usuario = ?");
$stmt->bind_param("is", $ruta_id, $_SESSION['usuario']);
$stmt->execute();
$resultado = $stmt->get_result();
$ruta = $resultado->fetch_assoc();

// Si no se encuentra la ruta o no pertenece al usuario, redirigir
if (!$ruta) {
    header("Location: consultaRuta.php");
    exit();
}

// Procesar el formulario de edición si se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origen = $_POST['origen'];
    $destino = $_POST['destino'];
    $fecha_origen = $_POST['fecha_origen'];
    $fecha_destino = $_POST['fecha_destino'];
    $numero_barco = $_POST['numero_barco'];
    $tipo_embarcacion = $_POST['tipo_embarcacion'];

    // Validaciones básicas (puedes agregar más)
    if (empty($origen) || empty($destino) || empty($fecha_origen) || empty($fecha_destino) || empty($numero_barco) || empty($tipo_embarcacion)) {
        $error_message = "Todos los campos son obligatorios.";
    } else {
        // Preparar la consulta para actualizar la ruta
        $stmt_update = $conn->prepare("UPDATE rutas SET origen = ?, destino = ?, fecha_origen = ?, fecha_destino = ?, numero_barco = ?, tipo_embarcacion = ? WHERE id = ? AND usuario = ?");
        $stmt_update->bind_param("ssssssis", $origen, $destino, $fecha_origen, $fecha_destino, $numero_barco, $tipo_embarcacion, $ruta_id, $_SESSION['usuario']);

        if ($stmt_update->execute()) {
            header("Location: consultaRuta.php?editado=true"); 
            exit();
        } else {
            $error_message = "Error al actualizar la ruta.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ruta - Sistema de Rutas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Css/formularioRuta.css">
    <style>
        .container-editar {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 500;
            margin-top: 0.5rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .alert-danger {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container-editar">
        <h1 class="text-center mb-4">Editar Ruta</h1>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="editarRuta.php?viaje=<?= htmlspecialchars($ruta_id) ?>">
            <div class="mb-3">
                <label for="origen" class="form-label">Origen:</label>
                <input type="text" class="form-control" id="origen" name="origen" value="<?= htmlspecialchars($ruta['origen']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino:</label>
                <input type="text" class="form-control" id="destino" name="destino" value="<?= htmlspecialchars($ruta['destino']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_origen" class="form-label">Fecha Salida:</label>
                <input type="date" class="form-control" id="fecha_origen" name="fecha_origen" value="<?= htmlspecialchars($ruta['fecha_origen']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_destino" class="form-label">Fecha Llegada:</label>
                <input type="date" class="form-control" id="fecha_destino" name="fecha_destino" value="<?= htmlspecialchars($ruta['fecha_destino']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="numero_barco" class="form-label">Número de Barco:</label>
                <input type="text" class="form-control" id="numero_barco" name="numero_barco" value="<?= htmlspecialchars($ruta['numero_barco']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo_embarcacion" class="form-label">Tipo de Embarcación:</label>
                <select class="form-select" id="tipo_embarcacion" name="tipo_embarcacion" required>
                    <option value="CARGA" <?= ($ruta['tipo_embarcacion'] == 'CARGA') ? 'selected' : '' ?>>Carga</option>
                    <option value="PASAJEROS" <?= ($ruta['tipo_embarcacion'] == 'PASAJEROS') ? 'selected' : '' ?>>Pasajeros</option>
                </select>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="consultaRuta.php" class="btn btn-secondary ms-2">
                    <i class="fas fa-ban"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
