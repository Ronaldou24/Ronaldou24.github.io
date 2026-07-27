<?php
include('admin/bd.php'); // Conexión a la base de datos
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$busqueda_id = $_GET['busqueda_id'] ?? ''; // Obtener el término de búsqueda por ID
$filtro_tipo = $_GET['filtro_tipo'] ?? ''; // Obtener el filtro de tipo de embarcación

$sql = "SELECT id, origen, destino, fecha_origen, fecha_destino, numero_barco, tipo_embarcacion FROM rutas WHERE usuario = ?";
$parametros = [$usuario];
$tipos_parametros = "s";
$condiciones = [];

// Aplicar filtro de búsqueda por ID de viaje
if (!empty($busqueda_id)) {
    $condiciones[] = "id = ?";
    $parametros[] = $busqueda_id;
    $tipos_parametros .= "i"; // 'i' porque id es un entero
}

// Aplicar filtro por tipo de embarcación
if (!empty($filtro_tipo)) {
    $condiciones[] = "tipo_embarcacion = ?";
    $parametros[] = $filtro_tipo;
    $tipos_parametros .= "s";
}

if (!empty($condiciones)) {
    $sql .= " AND " . implode(" AND ", $condiciones);
}

// Añadir ORDER BY para que los resultados sean consistentes
$sql .= " ORDER BY id DESC"; // Por ejemplo, ordenar por ID de forma descendente

$stmt = $conn->prepare($sql);

// Vincular parámetros dinámicamente
if (!empty($parametros)) {
    $stmt->bind_param($tipos_parametros, ...$parametros);
}

$stmt->execute();
$resultado = $stmt->get_result();

$eliminado = isset($_GET['eliminado']) && $_GET['eliminado'] == 'true';
$error_eliminar = isset($_GET['error_eliminar']) && $_GET['error_eliminar'] == 'true';
$ruta_agregada = isset($_GET['agregado']) && $_GET['agregado'] == 'true';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operaciones de Rutas - Sistema de Rutas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Importar las fuentes */
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@400;600;700&display=swap');

        /* Definición de Variables (Basadas en tu navbar) */
        :root {
            --primary-blue: #007bff; /* Azul vibrante */
            --light-blue: #e7f3ff; /* Azul muy claro para acentos sutiles */
            --dark-blue: #0056b3; /* Azul más oscuro para hover/activo */
            --text-color: #333; /* Color de texto oscuro */
            --light-gray: #f8f9fa; /* Para fondo del cuerpo */
            --white: #ffffff; /* Blanco */
            --shadow-light: rgba(0, 0, 0, 0.08); /* Sombra ligera */
            --border-color: #dee2e6; /* Color de borde para inputs, etc. */
            --success-green: #28a745; /* Verde para éxito */
            --danger-red: #dc3545; /* Rojo para peligro/error */
            --info-blue: #17a2b8; /* Azul para información */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 80px; /* Ajusta este valor si tu navbar es más alto */
            padding-bottom: 40px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 0 15px;
        }

        /* Contenedor principal de la página */
        .page-container {
            background-color: var(--white);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px var(--shadow-light);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Título de la página */
        h1 {
            color: var(--dark-blue);
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 2.5rem;
            text-align: center;
        }

        /* Estilos para el formulario de búsqueda */
        .search-form {
            display: flex;
            gap: 10px; /* Espacio entre el input y el botón */
            margin-bottom: 25px;
            max-width: 500px; /* Limita el ancho del formulario de búsqueda */
            margin-left: auto;
            margin-right: auto;
        }

        .search-form .form-control {
            border-radius: 8px;
            border-color: var(--border-color);
            padding: 10px 15px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .search-form .btn-outline-success {
            border-color: var(--success-green);
            color: var(--success-green);
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .search-form .btn-outline-success:hover {
            background-color: var(--success-green);
            color: var(--white);
            box-shadow: 0 2px 5px rgba(40, 167, 69, 0.2);
        }

        /* Estilos para el grupo de botones de filtro */
        .filter-buttons {
            display: flex;
            justify-content: center; /* Centra los botones de filtro */
            gap: 5px; /* Espacio entre los botones */
            margin-bottom: 30px;
            flex-wrap: wrap; /* Permite que los botones se envuelvan */
        }

        .filter-buttons .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-buttons .btn-outline-primary {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        .filter-buttons .btn-outline-primary:hover,
        .filter-buttons .btn-outline-primary.active {
            background-color: var(--primary-blue);
            color: var(--white);
            box-shadow: 0 2px 5px rgba(0, 123, 255, 0.2);
        }

        .filter-buttons .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-color);
        }

        .filter-buttons .btn-outline-secondary:hover,
        .filter-buttons .btn-outline-secondary.active {
            background-color: var(--text-color);
            color: var(--white);
            border-color: var(--text-color);
            box-shadow: 0 2px 5px rgba(51, 51, 51, 0.2);
        }

        /* Estilos para la tabla */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: var(--text-color);
            border-collapse: separate;
            border-spacing: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); /* Sombra para la tabla */
            border-radius: 10px; /* Bordes redondeados para la tabla */
            overflow: hidden; /* Asegura que los bordes redondeados se apliquen al contenido */
        }

        .table th,
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--border-color);
        }

        .table thead th {
            background-color: var(--primary-blue);
            color: var(--white);
            border-bottom: 2px solid var(--dark-blue);
            font-weight: 600;
            text-align: left;
        }

        /* Bordes redondeados para la tabla */
        .table thead tr:first-child th:first-child {
            border-top-left-radius: 10px;
        }
        .table thead tr:first-child th:last-child {
            border-top-right-radius: 10px;
        }
        .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }
        .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        .table tbody tr:hover {
            background-color: var(--light-blue);
            transition: background-color 0.3s ease;
        }

        /* Mensaje de no rutas encontradas */
        .alert-info {
            background-color: var(--light-blue);
            color: var(--dark-blue);
            border-color: var(--primary-blue);
            border-radius: 8px;
            padding: 20px;
            font-weight: 600;
            margin-top: 30px;
        }

        /* Botón "Regresar al Menú" */
        .btn-volver-custom {
            background-color: var(--light-gray);
            color: var(--dark-blue);
            border: 2px solid var(--primary-blue);
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            margin-top: 30px; /* Margen superior para separarlo de la tabla/alert */
        }

        .btn-volver-custom:hover {
            background-color: var(--primary-blue);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        }

        .btn-volver-custom i {
            margin-right: 8px;
            color: var(--dark-blue); /* Icono del color del texto por defecto */
        }
        .btn-volver-custom:hover i {
            color: var(--white); /* Icono blanco al hacer hover */
        }


        /* --- Estilos para las alertas dinámicas (copiados de listadoRutas) --- */
        #alert-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            width: 90%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .alert {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            opacity: 0;
            animation: fadeIn 0.5s ease-out forwards;
        }

        .alert.success {
            background-color: var(--success-green);
            color: var(--white);
        }

        .alert.danger {
            background-color: var(--danger-red);
            color: var(--white);
        }

        .alert .content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .alert .icon svg {
            width: 30px;
            height: 30px;
            fill: var(--white);
        }

        .alert .content p {
            margin: 0;
            font-weight: 600;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px) translateX(-50%);
            }
            to {
                opacity: 1;
                transform: translateY(0) translateX(-50%);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0) translateX(-50%);
            }
            to {
                opacity: 0;
                transform: translateY(-20px) translateX(-50%);
            }
        }

        /* Media Queries para responsividad */
        @media (max-width: 991px) {
            .page-container {
                padding: 25px;
            }
            h1 {
                font-size: 2rem;
            }
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            }
            .table thead th,
            .table tbody td {
                white-space: nowrap; /* Evita que el texto de las celdas se rompa */
            }
        }

        @media (max-width: 767px) {
            .search-form {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-buttons {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            .filter-buttons .btn {
                width: 100%; /* Botones de filtro ocupan todo el ancho */
            }
            .btn-volver-custom {
                width: calc(100% - 30px); /* Ajusta el ancho para que no toque los bordes del padding del body */
                margin-left: 15px;
                margin-right: 15px;
            }
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="page-container">
        <h1 class="text-center mb-4">
            <i class="fas fa-ship me-2"></i>Operaciones de Rutas Marítimas
        </h1>

        <div id="alert-container">
            </div>

        <div class="mb-3">
            <form action="" method="GET" class="search-form">
                <input class="form-control" type="search" name="busqueda_id" placeholder="Buscar por ID de viaje..." value="<?= htmlspecialchars($busqueda_id) ?>" aria-label="Buscar por ID">
                <button class="btn btn-outline-success" type="submit">
                    <i class="fas fa-search me-2"></i>Buscar
                </button>
            </form>
        </div>

        <div class="mb-3">
            <div class="filter-buttons">
                <a href="?filtro_tipo=CARGA" class="btn btn-outline-primary <?= $filtro_tipo === 'CARGA' ? 'active' : '' ?>">
                    <i class="fas fa-truck-loading me-2"></i>Carga
                </a>
                <a href="?filtro_tipo=PASAJEROS" class="btn btn-outline-primary <?= $filtro_tipo === 'PASAJEROS' ? 'active' : '' ?>">
                    <i class="fas fa-users me-2"></i>Pasajeros
                </a>
                <a href="?filtro_tipo=MIXTO" class="btn btn-outline-primary <?= $filtro_tipo === 'MIXTO' ? 'active' : '' ?>">
                    <i class="fas fa-ship me-2"></i>Mixto
                </a>
                <a href="operaciones.php" class="btn btn-outline-secondary <?= empty($filtro_tipo) && empty($busqueda_id) ? 'active' : '' ?>">
                    <i class="fas fa-redo me-2"></i>Todos
                </a>
            </div>
        </div>

        <?php if ($resultado->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Fecha Salida</th>
                            <th>Fecha Llegada</th>
                            <th>Barco</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($ruta = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($ruta['id']) ?></td>
                                <td><?= htmlspecialchars($ruta['origen']) ?></td>
                                <td><?= htmlspecialchars($ruta['destino']) ?></td>
                                <td><?= htmlspecialchars($ruta['fecha_origen']) ?></td>
                                <td><?= htmlspecialchars($ruta['fecha_destino']) ?></td>
                                <td><?= htmlspecialchars($ruta['numero_barco']) ?></td>
                                <td><?= htmlspecialchars($ruta['tipo_embarcacion']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>No se encontraron rutas con los criterios de búsqueda o filtro.
            </div>
        <?php endif; ?>

        <div class="text-center">
            <a href="index.php" class="btn btn-volver-custom">
                <i class="fas fa-home"></i> Regresar al Menú Principal
            </a>
        </div>

    </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertContainer = document.getElementById('alert-container');

        // Los SVGs se mantienen, pero con un color de relleno para que se vean bien sobre fondos de alerta
        const successSVG = `<svg width="50" height="50" id="Layer_1" style="enable-background:new 0 0 128 128;" version="1.1" viewBox="0 0 128 128" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><circle fill="none" cx="64" cy="64" r="64"/></g><g><path fill="currentColor" d="M54.3,97.2L24.8,67.7c-0.4-0.4-0.4-1,0-1.4l8.5-8.5c0.4-0.4,1-0.4,1.4,0L55,78.1l38.2-38.2   c0.4-0.4,1-0.4,1.4,0l8.5,8.5c0.4,0.4,0.4,1,0,1.4L55.7,97.2C55.3,97.6,54.7,97.6,54.3,97.2z"/></g></svg>`;
        const dangerSVG = `<svg height="50" viewBox="0 0 512 512" width="50" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M449.07,399.08,278.64,82.58c-12.08-22.44-44.26-22.44-56.35,0L51.87,399.08A32,32,0,0,0,80,446.25H420.89A32,32,0,0,0,449.07,399.08Zm-198.6-1.83a20,20,0,1,1,20-20A20,20,0,0,1,250.47,397.25ZM272.19,196.1l-5.74,122a16,16,0,0,1-32,0l-5.74-121.95v0a21.73,21.73,0,0,1,21.5-22.69h.21a21.74,21.74,0,0,1,21.73,22.7Z"/></svg>`;
        
        <?php if ($eliminado): ?>
            mostrarAlerta('Ruta eliminada correctamente.', 'danger');
        <?php endif; ?>

        <?php if ($error_eliminar): ?>
            mostrarAlerta('Error al eliminar la ruta. Inténtalo de nuevo.', 'danger');
        <?php endif; ?>

        <?php if ($ruta_agregada): ?>
            mostrarAlerta('Ruta agregada correctamente.', 'success');
        <?php endif; ?>

        function mostrarAlerta(mensaje, tipo) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${tipo}`; // Usa template literal para más facilidad

            const contentDiv = document.createElement('div');
            contentDiv.className = 'content';

            const iconDiv = document.createElement('div');
            iconDiv.className = 'icon';
            if (tipo === 'success') {
                iconDiv.innerHTML = successSVG;
            } else if (tipo === 'danger') {
                iconDiv.innerHTML = dangerSVG;
            }
            contentDiv.appendChild(iconDiv);

            const messageP = document.createElement('p');
            messageP.textContent = mensaje;
            contentDiv.appendChild(messageP);

            alertDiv.appendChild(contentDiv);
            alertContainer.appendChild(alertDiv);

            // Establece la propiedad fill de los SVG a currentColor para que herede el color del texto
            alertDiv.querySelectorAll('svg path').forEach(path => {
                path.setAttribute('fill', 'currentColor');
            });


            setTimeout(() => {
                alertDiv.style.animation = 'fadeOut 0.5s ease-in forwards'; 
                setTimeout(() => {
                    alertDiv.remove(); 
                }, 500); 
            }, 3000);
        }
    });
</script>
</body>
</html>