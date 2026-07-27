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
    <title>Éxito en operación</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Los mismos estilos de tu formulario */
        :root {
            --azul-profundo: #1a4a5a;
            --azul-turquesa: #2a7a8c;
            --azul-claro: #8fc7d8;
            --gris-acento: #6c757d;
            --blanco-espuma: #f0f8ff;
            --degradado-marino: linear-gradient(135deg, var(--azul-profundo), var(--azul-turquesa));
        }

        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(26, 74, 90, 0.1);
        }

        .btn-marino {
            background: var(--degradado-marino);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-marino:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 74, 90, 0.2);
        }

        .btn-volver {
            background: var(--gris-acento);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-volver:hover {
            background: #5a6268;
            color: var(--blanco-espuma);
            transform: translateY(-1px);
        }

        .botones-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .mensaje-exito {
            color: var(--azul-turquesa);
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
        <h3 class="mb-4 text-center text-azul-profundo">
            <i class="fas fa-check-circle me-2"></i>Operación Exitosa
        </h3>

        <div class="mensaje-exito">
            <?php 
            if(isset($_SESSION['mensaje'])) {
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }
            ?>
        </div>

        <div class="botones-container">
            <a href="../index.php" class="btn-marino">
                <i class="fas fa-home me-2"></i>Volver al Inicio
            </a>
            
            <a href="../operaciones.php" class="btn-volver">
                <i class="fas fa-table me-2"></i>Ver Tabla de Rutas
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>