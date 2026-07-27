<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error en operación</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Mismos estilos que exito.php */
        /* ... (pegar aquí los mismos estilos de exito.php) ... */

        .mensaje-error {
            color: #dc3545;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
<?php require_once '../admin/bd.php'; ?>

<div class="container my-5">
    <div class="form-container">
        <h3 class="mb-4 text-center text-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>Error en la Operación
        </h3>

        <div class="mensaje-error">
            <?php 
            if(isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
            ?>
        </div>

        <div class="botones-container">
            <a href="../index.php" class="btn-marino">
                <i class="fas fa-home me-2"></i>Volver al Inicio
            </a>
            
            <a href="vertablas.php" class="btn-volver">
                <i class="fas fa-table me-2"></i>Ver Tabla de Rutas
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>