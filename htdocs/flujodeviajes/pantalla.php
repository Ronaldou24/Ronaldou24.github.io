<?php
include('admin/bd.php'); // Conexión a la base de datos
session_start();

// Verificar si el usuario está autenticado (mantener la seguridad, aunque la consulta sea general)
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener la fecha y hora actuales en formato de base de datos
$current_datetime = new DateTime();
$current_timestamp = $current_datetime->getTimestamp();

// Calcular 24 horas antes y 24 horas después de la hora actual
$start_time_range = (new DateTime())->setTimestamp($current_timestamp - (24 * 3600))->format('Y-m-d H:i:s');
$end_time_range = (new DateTime())->setTimestamp($current_timestamp + (24 * 3600))->format('Y-m-d H:i:s');

// Consulta SQL para obtener los datos de las rutas dentro del rango de 24 horas
$sql = "SELECT
            r.id,
            r.usuario,
            r.origen,
            r.destino,
            r.fecha_origen,
            r.hora_salida,
            r.fecha_destino,
            r.hora_llegada,
            r.numero_barco,
            r.tipo_embarcacion,
            u.img AS user_image_name
        FROM
            rutas r
        INNER JOIN
            flujoviajes.usuarios u ON r.usuario = u.usuario
        WHERE
            STR_TO_DATE(CONCAT(r.fecha_origen, ' ', r.hora_salida), '%Y-%m-%d %H:%i:%s') BETWEEN ? AND ?
        ORDER BY
            r.fecha_origen ASC, r.hora_salida ASC";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Bind de los parámetros para el rango de tiempo
$stmt->bind_param("ss", $start_time_range, $end_time_range);
$stmt->execute();

if ($stmt->errno) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

$resultado = $stmt->get_result();

// Función para determinar el estado del viaje
function obtenerEstadoViaje($fecha_origen, $hora_salida) {
    $fecha_hora_salida = $fecha_origen . ' ' . $hora_salida;
    $timestamp_salida = strtotime($fecha_hora_salida);
    $timestamp_actual = time();
    $diferencia = $timestamp_salida - $timestamp_actual;

    if ($diferencia > 7200) { // Más de 2 horas (Programado)
        return ['estado' => 'PROGRAMADO', 'clase' => 'programado'];
    } elseif ($diferencia > 1800) { // Entre 30 min y 2 horas (Registro)
        return ['estado' => 'REGISTRO', 'clase' => 'registro'];
    } elseif ($diferencia > 900) { // Entre 15 y 30 min (Abordando)
        return ['estado' => 'ABORDANDO', 'clase' => 'abordando'];
    } elseif ($diferencia > -1800) { // Hasta 30 min después (Navegando)
        return ['estado' => 'NAVEGANDO', 'clase' => 'navegando'];
    } else { // Más de 30 min después (Arribado)
        return ['estado' => 'ARRIBADO', 'clase' => 'arribado'];
    }
}

?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Marítima Puerto Vallarta - Salidas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background-color: #000;
            color: #ffff00;
            overflow-x: hidden; /* Evita que el scroll horizontal afecte el body */
            min-height: 100vh;
        }

        /* AJUSTE CLAVE: Aumentar el max-width y padding del contenedor principal */
        .container {
            width: 95%; /* Usar un porcentaje para mayor flexibilidad */
            max-width: 1400px; /* Aumentar el ancho máximo del tablero */
            margin: 20px auto; /* Centrar y agregar margen superior/inferior */
            padding: 20px 30px; /* Aumentar el padding horizontal para el "marco" */
            border: 2px solid #ffff00; /* Borde para que actúe como un marco */
            background-color: #000; /* Fondo del "marco" */
        }

        /* Header Section */
        .header-board {
            background-color: #000;
            color: #ffff00;
            padding: 10px 0 20px 0; /* Ajustar padding si es necesario, ya tiene padding del container */
            text-align: center;
            border-bottom: 2px solid #ffff00; /* Línea separadora */
            margin-bottom: 15px;
        }

        .board-title {
            font-size: 2.8em; /* Un poco más grande para el título principal */
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 10px;
            text-shadow: 0 0 10px #ffff00, 0 0 20px #ffff00; /* Efecto neón más pronunciado */
        }

        .terminal-info {
            font-size: 1.3em; /* Un poco más grande */
            margin-bottom: 15px;
        }

        .datetime-info {
            font-size: 1.1em; /* Un poco más grande */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 10px; /* Pequeño padding para no pegar a los bordes si el container tiene poco */
        }

        /* Departures Board */
        .departures-board {
            background-color: #000;
            /* Ya no necesita un borde propio si el container es el marco */
            overflow: hidden; /* Esto es importante para el scroll interno de la tabla si es necesario */
        }

        .board-header {
            background-color: #ffff00;
            color: #000;
            padding: 15px;
            text-align: center;
            font-size: 1.6em; /* Más grande para el encabezado de la tabla */
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px; /* Espacio debajo del header */
        }

        /* Nuevo contenedor para la tabla que permite el scroll horizontal */
        .table-scroll-wrapper {
            overflow-x: auto; /* Permite el scroll horizontal si la tabla es más ancha que su contenedor */
            -webkit-overflow-scrolling: touch; /* Suaviza el scroll en iOS */
        }

        .departures-table {
            width: 100%;
            min-width: 900px; /* Ajustado el ancho mínimo de la tabla */
            border-collapse: collapse;
            background-color: #000;
            color: #ffff00;
            table-layout: fixed; /* Esto es clave para controlar el ancho de las columnas */
        }

        .departures-table th,
        .departures-table td {
            padding: 12px 8px;
            text-align: left;
            font-size: 1em; /* Un poco más grande para mejor lectura */
            vertical-align: middle; /* Asegura el centrado vertical del contenido */
            border-bottom: 1px solid #333; /* Mantener bordes de las celdas */
            word-wrap: break-word; /* Romper palabras largas si no caben */
            white-space: normal; /* Permitir múltiples líneas de texto */
        }

        .departures-table th {
            background-color: #333;
            color: #ffff00;
            font-weight: bold;
            border-bottom: 2px solid #ffff00;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Definir anchos de columna para 10 columnas (eliminando TRAVESÍA) */
        /* USUARIO + ORIGEN + DESTINO + FECHA SALIDA + HORA SALIDA + FECHA LLEGADA + HORA LLEGADA + NÚMERO BARCO + TIPO EMBARCACIÓN + ESTADO */
        /* Suma anterior de las 10 columnas restantes (sin TRAVESÍA): 9% + 8% + 11% + 9% + 8% + 9% + 8% + 11% + 10% + 9% = 92% */
        /* Distribuiremos el 8% restante. Aumentaremos un poco más ORIGEN, DESTINO, NÚMERO BARCO y TIPO EMBARCACIÓN */

        .departures-table th:nth-child(1), .departures-table td:nth-child(1) { width: 10%; text-align: center; } /* USUARIO - Aumentado */
        .departures-table th:nth-child(2), .departures-table td:nth-child(2) { width: 9%; } /* ORIGEN - Aumentado */
        .departures-table th:nth-child(3), .departures-table td:nth-child(3) { width: 13%; } /* DESTINO - Aumentado */
        .departures-table th:nth-child(4), .departures-table td:nth-child(4) { width: 10%; } /* FECHA SALIDA */
        .departures-table th:nth-child(5), .departures-table td:nth-child(5) { width: 9%; } /* HORA SALIDA */
        .departures-table th:nth-child(6), .departures-table td:nth-child(6) { width: 10%; } /* FECHA LLEGADA */
        .departures-table th:nth-child(7), .departures-table td:nth-child(7) { width: 9%; } /* HORA LLEGADA */
        .departures-table th:nth-child(8), .departures-table td:nth-child(8) { width: 12%; } /* NÚMERO BARCO - Aumentado */
        .departures-table th:nth-child(9), .departures-table td:nth-child(9) { width: 10%; } /* TIPO EMBARCACIÓN - Ajustado para sumar 100% */
        .departures-table th:nth-child(10), .departures-table td:nth-child(10) { width: 8%; } /* ESTADO - Ajustado para sumar 100% */
        /* Suma: 10 + 9 + 13 + 10 + 9 + 10 + 9 + 12 + 10 + 8 = 100% */


        .departures-table tbody tr {
            background-color: #000;
            transition: background-color 0.3s ease;
        }

        .departures-table tbody tr:hover {
            background-color: #111;
        }

        .departures-table tbody tr:nth-child(even) {
            background-color: #0a0a0a;
        }

        /* Status Styles */
        .status {
            display: inline-block;
            padding: 6px 10px; /* Un poco más de padding */
            font-weight: bold;
            font-size: 0.9em; /* Más legible */
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 4px; /* Bordes un poco más suaves */
            min-width: 95px; /* Asegurar un ancho mínimo */
            text-align: center;
        }

        .status.programado { background-color: #00cc00; color: #000; } /* Verde más brillante */
        .status.registro { background-color: #ff9900; color: #000; }
        .status.abordando { background-color: #0066ff; color: #fff; } /* Azul más vibrante */
        .status.navegando { background-color: #9900ff; color: #fff; }
        .status.arribado { background-color: #666; color: #fff; }

        /* User Image */
        .user-image {
            width: 35px; /* Aumentar el tamaño de la imagen */
            height: 35px; /* Aumentar el tamaño de la imagen */
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #ffff00;
            display: block; /* Asegura que la imagen sea un bloque para margin auto */
            margin: 0 auto; /* Centrar horizontalmente la imagen dentro de su celda */
        }

        /* Special Text Styles (voyage-id eliminado) */
        /* .voyage-id {
            font-weight: bold;
            color: #00ff00;
            font-size: 1.2em;
        } */

        .destination-text {
            font-weight: bold;
            color: #ffff00;
            text-transform: uppercase;
        }

        .destination-code {
            display: block;
            font-size: 0.8em; /* Más legible */
            color: #ccc;
            margin-top: 2px;
        }

        .time-display {
            font-family: 'Courier New', monospace;
            color: #00ff00;
            font-weight: bold;
            font-size: 1.1em;
        }

        .dock-number {
            color: #ff9900;
            font-weight: bold;
        }

        .vessel-details {
            color: #ccc;
            text-transform: uppercase;
        }

        .origin-text {
            color: #ffff00;
            text-transform: uppercase;
        }

        /* No vessels message */
        .no-vessels {
            text-align: center;
            padding: 50px;
            font-size: 1.5em;
            color: #ffff00;
            background-color: #000;
            border: 2px dashed #ffff00;
            margin: 20px 0;
        }

        .no-vessels .ship-icon {
            display: block;
            font-size: 3em;
            margin-bottom: 15px;
            color: #ffff00;
        }

        /* Back Button */
        .back-section {
            margin: 30px 0;
            text-align: center;
        }

        .btn-back {
            display: inline-block;
            background-color: #ffff00;
            border: 2px solid #ffff00;
            color: #000;
            padding: 15px 30px;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #000;
            color: #ffff00;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .container {
                width: 98%;
                padding: 15px 20px;
            }
            .board-title { font-size: 2.2em; }
            .terminal-info { font-size: 1.1em; }
            .datetime-info { font-size: 1em; }
            .departures-table th, .departures-table td { font-size: 0.9em; padding: 10px 6px; }
            .user-image { width: 30px; height: 30px; }
            .status { font-size: 0.8em; min-width: 80px; padding: 5px 8px; }

            /* Ajustes de anchos para 1024px (10 columnas) */
            /* Suma: 10 + 9 + 13 + 10 + 9 + 10 + 9 + 12 + 10 + 8 = 100% */
            .departures-table th:nth-child(1), .departures-table td:nth-child(1) { width: 10%; } /* USUARIO */
            .departures-table th:nth-child(2), .departures-table td:nth-child(2) { width: 9%; } /* ORIGEN */
            .departures-table th:nth-child(3), .departures-table td:nth-child(3) { width: 13%; } /* DESTINO */
            .departures-table th:nth-child(4), .departures-table td:nth-child(4) { width: 10%; } /* FECHA SALIDA */
            .departures-table th:nth-child(5), .departures-table td:nth-child(5) { width: 9%; } /* HORA SALIDA */
            .departures-table th:nth-child(6), .departures-table td:nth-child(6) { width: 10%; } /* FECHA LLEGADA */
            .departures-table th:nth-child(7), .departures-table td:nth-child(7) { width: 9%; } /* HORA LLEGADA */
            .departures-table th:nth-child(8), .departures-table td:nth-child(8) { width: 12%; } /* NÚMERO BARCO */
            .departures-table th:nth-child(9), .departures-table td:nth-child(9) { width: 10%; } /* TIPO EMBARCACIÓN */
            .departures-table th:nth-child(10), .departures-table td:nth-child(10) { width: 8%; } /* ESTADO */
        }


        @media (max-width: 768px) {
            .container {
                margin: 10px auto;
                padding: 10px 15px;
            }

            .board-title {
                font-size: 1.8em;
                letter-spacing: 2px;
            }

            .board-header {
                font-size: 1.3em;
            }

            .departures-table th,
            .departures-table td {
                padding: 8px 4px;
                font-size: 0.85em;
            }

            .datetime-info {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start; /* Alinea el texto a la izquierda en columna */
            }

            .user-image {
                width: 28px;
                height: 28px;
            }

            .status {
                font-size: 0.75em;
                padding: 4px 7px;
                min-width: 70px;
            }
            /* Min-width de la tabla para forzar el scroll horizontal */
            .departures-table {
                min-width: 800px; /* Asegura el scroll si no cabe */
            }
        }

        @media (max-width: 480px) {
            .board-title {
                font-size: 1.5em;
                letter-spacing: 1px;
            }

            .terminal-info {
                font-size: 1em;
            }

            .board-header {
                font-size: 1.1em;
            }

            .departures-table th,
            .departures-table td {
                padding: 6px 3px;
                font-size: 0.75em;
            }

            .btn-back {
                padding: 10px 15px;
                font-size: 0.9em;
            }
            .user-image {
                width: 25px;
                height: 25px;
            }
            .status {
                font-size: 0.7em;
                min-width: 60px;
            }
            /* Min-width de la tabla para forzar el scroll horizontal en móviles muy pequeños */
            .departures-table {
                min-width: 700px; /* Ajuste adicional para el scroll */
            }
        }

        /* Blinking effect for certain elements */
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }

        .blink {
            animation: blink 2s infinite;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-board">
            <h1 class="board-title">TERMINAL MARÍTIMA PUERTO VALLARTA</h1>
            <div class="terminal-info">SALIDAS - DEPARTURES</div>
            <div class="datetime-info">
                <span id="currentTime"></span>
                <span>TERMINAL PVR</span>
                <span id="currentDate"></span>
            </div>
        </div>

        <div class="departures-board">
            <div class="board-header">
                INFORMACIÓN DE SALIDAS
            </div>

            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <div class="table-scroll-wrapper"> <table class="departures-table">
                        <thead>
                            <tr>
                                <th>USUARIO</th>
                                <th>ORIGEN</th>
                                <th>DESTINO</th>
                                <th>FECHA SALIDA</th>
                                <th>HORA SALIDA</th>
                                <th>FECHA LLEGADA</th>
                                <th>HORA LLEGADA</th>
                                <th>NÚMERO BARCO</th>
                                <th>TIPO EMBARCACIÓN</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($ruta = $resultado->fetch_assoc()): ?>
                                <?php
                                    $estado_info = obtenerEstadoViaje($ruta['fecha_origen'], $ruta['hora_salida']);
                                    $destino_code = strtoupper(substr($ruta['destino'], 0, 3));
                                    $user_image_name = htmlspecialchars($ruta['user_image_name']);
                                    $user_image_path = 'uploads/usuarios/' . $user_image_name;
                                ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($user_image_name) && file_exists($user_image_path)): ?>
                                            <img src="<?= $user_image_path ?>" alt="Foto de <?= htmlspecialchars($ruta['usuario']) ?>" class="user-image">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/35/ffff00/000000?text=U" alt="Usuario" class="user-image">
                                        <?php endif; ?>
                                    </td>
                                    <td class="origin-text"><?= strtoupper(htmlspecialchars($ruta['origen'])) ?></td>
                                    <td>
                                        <div class="destination-text"><?= strtoupper(htmlspecialchars($ruta['destino'])) ?></div>
                                        <span class="destination-code"><?= $destino_code ?></span>
                                    </td>
                                    <td class="time-display">
                                        <?= htmlspecialchars($ruta['fecha_origen']) ?>
                                    </td>
                                    <td class="time-display">
                                        <?php if (isset($ruta['hora_salida'])): ?>
                                            <?= date('H:i', strtotime($ruta['hora_salida'])) ?>
                                        <?php else: ?>
                                            --:--
                                        <?php endif; ?>
                                    </td>
                                    <td class="time-display">
                                        <?= htmlspecialchars($ruta['fecha_destino']) ?>
                                    </td>
                                    <td class="time-display">
                                        <?php if (isset($ruta['hora_llegada'])): ?>
                                            <?= date('H:i', strtotime($ruta['hora_llegada'])) ?>
                                        <?php else: ?>
                                            --:--
                                        <?php endif; ?>
                                    </td>
                                    <td class="vessel-details"><?= strtoupper(htmlspecialchars($ruta['numero_barco'])) ?></td>
                                    <td class="vessel-details"><?= strtoupper(htmlspecialchars($ruta['tipo_embarcacion'])) ?></td>
                                    <td>
                                        <span class="status <?= $estado_info['clase'] ?>">
                                            <?= $estado_info['estado'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div> <?php else: ?>
                <div class="no-vessels">
                    <span class="ship-icon">⚓</span>
                    <div>NO HAY TRAVESÍAS PROGRAMADAS EN LAS PRÓXIMAS 24 HORAS</div>
                    <div style="font-size: 0.8em; margin-top: 15px;">
                        Consulte más tarde para actualizaciones
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="back-section">
            <a href="index.php" class="btn-back">← VOLVER AL MENÚ PRINCIPAL</a>
        </div>
    </div>

    <script>
        // Función para actualizar la hora y fecha actuales
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-MX', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const dateString = now.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });

            document.getElementById('currentTime').textContent = timeString;
            document.getElementById('currentDate').textContent = dateString.toUpperCase();
        }

        // Actualizar tiempo cada segundo
        updateTime();
        setInterval(updateTime, 1000);

        // Añadir efecto de parpadeo a algunos elementos críticos
        setInterval(() => {
            const criticalElements = document.querySelectorAll('.status.navegando, .status.abordando');
            criticalElements.forEach(el => {
                el.classList.toggle('blink');
            });
        }, 3000);
    </script>
</body>
</html>