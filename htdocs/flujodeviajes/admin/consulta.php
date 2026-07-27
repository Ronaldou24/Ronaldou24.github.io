<?php
include('../admin/bd.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT id, usuario, rango, img FROM usuarios"); 
$stmt->execute();
$resultado = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>

        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 1em 0;
            text-align: center;
            position: relative;
        }

        .home-button {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .home-button i {
            margin-right: 5px;
        }

        .home-button:hover {
            background-color: #45a049;
        }

        main {
            padding: 20px;
            display: flex;
            justify-content: center; 
            align-items: flex-start;
            flex-wrap: wrap; 
            gap: 20px; 
        }

        .card {
            width: 100%; 
            max-width: 900px; 
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: stretch; 
        }

        .card-header {
            background-color: #f0f0f0;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd; 
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }

        .card-content {
            padding: 20px;
        }

        .table-responsive {
            overflow-x: auto; 
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

       
        .alert {
            display: flex;
            align-items:center;
            padding: 0.55rem 0.65rem 0.55rem 0.75rem;
            border-radius:1rem;
            min-width:300px;
            justify-content: space-between;
            margin-bottom: 1rem;
            box-shadow:
            0px 3.2px 13.8px rgba(0, 0, 0, 0.02),
            0px 7.6px 33.3px rgba(0, 0, 0, 0.028),
            0px 14.4px 62.6px rgba(0, 0, 0, 0.035),
            0px 25.7px 111.7px rgba(0, 0, 0, 0.042),
            0px 48px 208.9px rgba(0, 0, 0, 0.05),
            0px 115px 500px rgba(0, 0, 0, 0.07);
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            opacity: 0.98;
            animation: slideDown 0.5s ease-out forwards;
        }
        .alert .content{
            display: flex;
            align-items:center;
        }
        .alert .icon{
            padding: 0.5rem;
            margin-right: 1rem;
            border-radius:39% 61% 42% 58% / 50% 51% 49% 50%;
            box-shadow:
            0px 3.2px 13.8px rgba(0, 0, 0, 0.02),
            0px 7.6px 33.3px rgba(0, 0, 0, 0.028),
            0px 14.4px 62.6px rgba(0, 0, 0, 0.035),
            0px 25.7px 111.7px rgba(0, 0, 0, 0.042),
            0px 48px 208.9px rgba(0, 0, 0, 0.05),
            0px 115px 500px rgba(0, 0, 0, 0.07);
        }
        .alert p {
            margin-bottom: 0;
        }

        .alert.success{
            background-color: rgba(62, 189, 97,0.2);
            border:2px solid #3ebd61;
            color: #333C48;
        }
        .alert.success .icon{
            background-color:#3ebd61;
        }
        .alert.success .icon svg path {
            fill: #fff;
        }

        .alert.danger{
            background-color: rgba(236, 77, 43, 0.2);
            border:2px solid #EC4D2B;
            color: #333C48;
        }
        .alert.danger .icon{
            background-color: #EC4D2B;
        }
        .alert.danger .icon svg path {
            fill: #fff;
        }

        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 0.98;
            }
        }
        @keyframes fadeOut {
            from {
                opacity: 0.98;
            }
            to {
                opacity: 0;
            }
        }



        @media (max-width: 768px) {
            main {
                flex-direction: column; 
                align-items: center; 
            }

            .card {
                width: 95%; 
                margin: 10px 0; 
            }
            
            .home-button {
                position: relative;
                left: auto;
                top: auto;
                transform: none;
                margin: 10px auto;
                display: block;
            }
        }
    </style>
</head>
<body>

    <header>
        <a href="gestionusuarios.php" class="home-button">
            <i class="fas fa-home"></i> Regresar
        </a>
        <h1>Consulta de Usuarios</h1>
    </header>

    <main>
        <div class="card">
            <div class="card-header">
                <h2>Usuarios Registrados</h2>
            </div>
            <div class="card-content">
                <div id="alert-container">

                </div>
                <div class="table-responsive">
                    <?php if ($resultado->num_rows > 0): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Usuario</th>
                                    <th>Rango</th>
                                    <th>Imagen</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($usuario = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($usuario['id']) ?></td>
                                        <td><?= htmlspecialchars($usuario['usuario']) ?></td>
                                        <td><?= htmlspecialchars($usuario['rango']) ?></td>
                                        <td><?= htmlspecialchars($usuario['img']) ?></td>
                                        

                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">
                            No hay usuarios registrados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Mi Aplicación</p>
    </footer>
</body>
</html>