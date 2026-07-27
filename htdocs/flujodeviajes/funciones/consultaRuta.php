<?php
include('../admin/bd.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT id, origen, destino, fecha_origen, fecha_destino, numero_barco, tipo_embarcacion FROM rutas WHERE usuario = ?");
$stmt->bind_param("s", $_SESSION['usuario']);
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
    <title>Listado de Rutas - Sistema de Rutas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@400;600;700&display=swap');

        :root {
            --primary-blue: #007bff; 
            --light-blue: #e7f3ff; 
            --dark-blue: #0056b3; 
            --text-color: #333; 
            --light-gray: #f8f9fa; /* Para fondo del cuerpo */
            --white: #ffffff; /* Blanco */
            --shadow-light: rgba(0, 0, 0, 0.08); /* Sombra ligera */
            --border-color: #dee2e6; /* Color de borde para inputs, etc. */
            --success-green: #28a745; /* Verde para éxito */
            --danger-red: #dc3545; /* Rojo para peligro/error */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Alinea al inicio para que el contenido de la tabla no se "centre" visualmente demasiado abajo */
            min-height: 100vh;
            padding-top: 80px; /* Ajusta este valor si tu navbar es más alto */
            padding-bottom: 40px; /* Espacio inferior para el botón */
        }

        .container {
            width: 100%; /* El contenedor puede ser más ancho para la tabla */
            max-width: 1200px; /* Ancho máximo para el contenido principal */
            padding: 0 15px; /* Padding a los lados */
        }

        /* Contenedor principal de la página (no el de formulario de antes) */
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
            font-size: 2.5rem; /* Un poco más grande para el título principal */
            text-align: center;
        }

        /* Estilos para la tabla */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: var(--text-color);
            border-collapse: separate; /* Necesario para border-radius en celdas */
            border-spacing: 0; /* Elimina el espacio entre celdas */
        }

        .table th,
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--border-color);
        }

        .table thead th {
            background-color: var(--primary-blue); /* Fondo azul para el encabezado */
            color: var(--white); /* Texto blanco en el encabezado */
            border-bottom: 2px solid var(--dark-blue); /* Borde más oscuro debajo del encabezado */
            font-weight: 600;
            text-align: left;
        }

        /* Bordes redondeados para la tabla */
        .table thead tr:first-child th:first-child {
            border-top-left-radius: 10px; /* Esquinas redondeadas arriba a la izquierda */
        }

        .table thead tr:first-child th:last-child {
            border-top-right-radius: 10px; /* Esquinas redondeadas arriba a la derecha */
        }

        .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 10px; /* Esquinas redondeadas abajo a la izquierda */
        }

        .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px; /* Esquinas redondeadas abajo a la derecha */
        }


        .table tbody tr:hover {
            background-color: var(--light-blue); /* Fondo más claro al pasar el ratón */
            transition: background-color 0.3s ease;
        }

        /* Contenedor para los botones de acción en la tabla */
        .action-buttons {
            display: flex; /* Usar flexbox para alinear */
            gap: 8px; /* Espacio entre los botones */
            justify-content: flex-start; /* Alinea los botones al inicio de la celda */
            align-items: center; /* Centra verticalmente */
            flex-wrap: wrap; /* Permite que los botones se envuelvan si no hay espacio */
        }

        /* Estilos para los botones de acción en la tabla */
        .table .btn {
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
            border-radius: 6px; /* Bordes ligeramente redondeados */
            transition: all 0.3s ease;
            display: inline-flex; /* Para alinear ícono y texto */
            align-items: center;
            justify-content: center;
            flex-shrink: 0; /* Evita que los botones se encojan */
        }

        .table .btn i {
            margin-right: 5px; /* Espacio para el ícono */
        }

        .table .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .table .btn-primary:hover {
            background-color: var(--dark-blue);
            border-color: var(--dark-blue);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        .table .btn-danger {
            background-color: var(--danger-red);
            border-color: var(--danger-red);
        }

        .table .btn-danger:hover {
            background-color: #c82333; /* Un rojo un poco más oscuro */
            border-color: #bd2130;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }

        /* Mensaje de no rutas */
        .alert-warning {
            background-color: var(--light-blue);
            color: var(--dark-blue);
            border-color: var(--primary-blue);
            border-radius: 8px;
            padding: 20px;
            font-weight: 600;
            margin-top: 30px;
        }

        /* Contenedor para los botones inferiores (Crear y Regresar) */
        .bottom-buttons-container {
            display: flex;
            justify-content: center; /* Centra los botones */
            gap: 20px; /* Espacio entre los botones */
            margin-top: 40px; /* Margen superior para separarlos de la tabla */
            flex-wrap: wrap; /* Permite que los botones se envuelvan en pantallas pequeñas */
        }

        /* Estilo general para los botones inferiores */
        .bottom-buttons-container .btn {
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none; /* Asegúrate de que los enlaces no se subrayen */
        }

        .bottom-buttons-container .btn i {
            margin-right: 8px;
        }

        /* Botón "Crear Nueva Ruta" */
        .btn-success-custom { /* Cambiado a custom para evitar conflicto con la clase base de bootstrap .btn-success */
            background-color: var(--success-green);
            border-color: var(--success-green);
            color: var(--white);
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }

        .btn-success-custom:hover {
            background-color: #218838; /* Verde más oscuro */
            border-color: #1e7e34;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.4);
            color: var(--white); /* Asegura que el texto siga siendo blanco */
        }

        /* Botón "Regresar" */
        .btn-volver-custom { /* Similar al btn-volver de agregarRuta.css */
            background-color: var(--light-gray);
            color: var(--dark-blue);
            border: 2px solid var(--primary-blue);
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.1);
        }

        .btn-volver-custom:hover {
            background-color: var(--primary-blue);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        }

        .btn-volver-custom:hover i {
            color: var(--white); /* El icono también se vuelve blanco al hacer hover */
        }

        /* --- Estilos para las alertas dinámicas --- */
        #alert-container {
            position: fixed; /* Fijo en la pantalla */
            top: 20px; /* Desde arriba */
            left: 50%; /* Centrado horizontalmente */
            transform: translateX(-50%); /* Ajuste para centrar */
            z-index: 1050; /* Por encima de la mayoría de los elementos */
            width: 90%;
            max-width: 400px; /* Ancho máximo para alertas */
            display: flex;
            flex-direction: column;
            gap: 10px; /* Espacio entre alertas si hubiera varias */
        }

        .alert {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-radius: 10px; /* Bordes redondeados para alertas */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            opacity: 0; /* Inicialmente oculto para la animación */
            animation: fadeIn 0.5s ease-out forwards; /* Animación de entrada */
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
            gap: 15px; /* Espacio entre ícono y texto en la alerta */
        }

        .alert .icon svg {
            width: 30px; /* Tamaño del SVG dentro de la alerta */
            height: 30px;
            fill: var(--white); /* Color del SVG */
        }

        .alert .content p {
            margin: 0; /* Quitar margen por defecto del párrafo */
            font-weight: 600;
        }

        /* Animaciones */
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
            .container {
                padding-top: 20px; /* Menos padding superior en móvil */
                padding-bottom: 20px;
            }
            .page-container {
                padding: 25px; /* Menos padding en pantallas pequeñas */
            }
            h1 {
                font-size: 2rem; /* Título más pequeño en móvil */
            }
            /* La tabla se hará scrollable en pantallas pequeñas */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch; /* Suaviza el scroll en iOS */
                border-radius: 10px; /* Aplica redondeo al contenedor de la tabla si es scrollable */
                box-shadow: 0 4px 10px rgba(0,0,0,0.05); /* Sombra para la tabla scrollable */
            }

            .table thead th,
            .table tbody td {
                white-space: nowrap; /* Evita que el texto de las celdas se rompa */
            }

            /* Ajuste de botones en móvil para la tabla */
            .table .btn {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
            }
            /* Asegura que los botones de acción se apilen si no hay espacio */
            .action-buttons {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px; /* Menos espacio cuando están apilados */
            }
        }

        @media (max-width: 767px) {
            body {
                align-items: flex-start; /* Asegura que no haya centrado vertical excesivo */
            }
            .container {
                max-width: 100%; /* El contenedor ocupa todo el ancho */
            }
            .bottom-buttons-container {
                flex-direction: column; /* Apila los botones inferiores en móviles */
                gap: 15px;
            }
            .bottom-buttons-container .btn {
                width: calc(100% - 30px); /* Ajusta el ancho para que no toque los bordes del padding del body */
                margin-left: 15px; /* Centrar manualmente */
                margin-right: 15px;
            }
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="page-container">
        <h1 class="text-center mb-4">
            <i class="fas fa-list-alt me-2"></i>Mis Rutas
        </h1>

        <div id="alert-container">
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
                            <th>Acciones</th>
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
                                <td>
                                    <div class="action-buttons">
                                        <a href="editarRuta.php?viaje=<?= $ruta['id'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a href="eliminarRuta.php?viaje=<?= $ruta['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta ruta?')">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                <i class="fas fa-info-circle me-2"></i>No tienes rutas registradas.
            </div>
        <?php endif; ?>

        <div class="bottom-buttons-container">
            <a href="agregarRuta.php" class="btn btn-success-custom">
                <i class="fas fa-plus"></i> Crear Nueva Ruta
            </a>
            <a href="../gesionRutas.php" class="btn btn-volver-custom">
                <i class="fas fa-home"></i> Regresar al Inicio
            </a>
        </div>

    </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertContainer = document.getElementById('alert-container');

        const successSVG = `<svg width="50" height="50" id="Layer_1" style="enable-background:new 0 0 128 128;" version="1.1" viewBox="0 0 128 128" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><circle fill="none" cx="64" cy="64" r="64"/></g><g><path fill="currentColor" d="M54.3,97.2L24.8,67.7c-0.4-0.4-0.4-1,0-1.4l8.5-8.5c0.4-0.4,1-0.4,1.4,0L55,78.1l38.2-38.2   c0.4-0.4,1-0.4,1.4,0l8.5,8.5c0.4-0.4,0.4-1,0,1.4L55.7,97.2C55.3,97.6,54.7,97.6,54.3,97.2z"/></g></svg>`;
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
            // Esto es importante si el SVG no tiene un color de relleno explícito dentro de sí mismo
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