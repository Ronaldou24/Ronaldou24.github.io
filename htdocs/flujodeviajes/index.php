<?php
session_start();

include('admin/bd.php');

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$current_datetime = new DateTime();
$current_timestamp = $current_datetime->getTimestamp();

$start_time_range = (new DateTime())->setTimestamp($current_timestamp - (24 * 3600))->format('Y-m-d H:i:s');
$end_time_range = (new DateTime())->setTimestamp($current_timestamp + (24 * 3600))->format('Y-m-d H:i:s');

$sql_rutas = "SELECT
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

$stmt_rutas = $conn->prepare($sql_rutas);

if ($stmt_rutas === false) {
    die("Error al preparar la consulta de rutas: " . $conn->error);
}

$stmt_rutas->bind_param("ss", $start_time_range, $end_time_range);
$stmt_rutas->execute();

if ($stmt_rutas->errno) {
    die("Error al ejecutar la consulta de rutas: " . $stmt_rutas->error);
}

$resultado_rutas = $stmt_rutas->get_result();
$rutas = $resultado_rutas->fetch_all(MYSQLI_ASSOC);
$stmt_rutas->close(); // Cerrar el statement

// Función para determinar el estado del viaje
function obtenerEstadoViaje($fecha_origen, $hora_salida) {
    $fecha_hora_salida = $fecha_origen . ' ' . $hora_salida;
    $timestamp_salida = strtotime($fecha_hora_salida);
    $timestamp_actual = time();
    $diferencia = $timestamp_salida - $timestamp_actual;

    if ($diferencia > 7200) { // Más de 2 horas (Programado)
        return ['estado' => 'PROGRAMADO', 'clase' => 'info']; // Bootstrap class for info
    } elseif ($diferencia > 1800) { // Entre 30 min y 2 horas (Registro)
        return ['estado' => 'REGISTRO', 'clase' => 'warning']; // Bootstrap class for warning
    } elseif ($diferencia > 900) { // Entre 15 y 30 min (Abordando)
        return ['estado' => 'ABORDANDO', 'clase' => 'primary']; // Bootstrap class for primary
    } elseif ($diferencia > -1800) { // Hasta 30 min después (Navegando)
        return ['estado' => 'NAVEGANDO', 'clase' => 'success']; // Bootstrap class for success
    } else { // Más de 30 min después (Arribado)
        return ['estado' => 'ARRIBADO', 'clase' => 'secondary']; // Bootstrap class for secondary (grey)
    }
}

// --- Contadores sencillos para "Info Cards" ---
$total_rutas_proximas = count($rutas); // Número de rutas en el rango de 24h
$total_embarcaciones_activas = 0; // Contar rutas que NO han arribado
foreach ($rutas as $ruta) {
    $estado_info = obtenerEstadoViaje($ruta['fecha_origen'], $ruta['hora_salida']);
    if ($estado_info['estado'] != 'ARRIBADO') {
        $total_embarcaciones_activas++;
    }
}

// Obtener el número total de usuarios (simple SELECT COUNT)
$sql_total_usuarios = "SELECT COUNT(*) as total FROM usuarios";
$result_total_usuarios = $conn->query($sql_total_usuarios);
$total_usuarios = 0;
if ($result_total_usuarios) {
    $row = $result_total_usuarios->fetch_assoc();
    $total_usuarios = $row['total'];
    $result_total_usuarios->free();
}

$conn->close(); // Cerrar la conexión después de todas las consultas
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarTransport - Panel de Control</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #007bff;
            --dark-blue: #0056b3;
            --light-gray: #f8f9fa;
            --white: #ffffff;
            --text-color: #343a40;
            --shadow-light: rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 70px; /* Adjust for fixed navbar */
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Hero/Banner Section */
        .hero-banner {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            padding: 3rem 0;
            color: var(--white);
            text-align: center;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .hero-banner h1 {
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 0.5rem;
        }
        .hero-banner p.lead {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Card Styles (Info Cards & Table Card) */
        .custom-card {
            background: var(--white);
            border-radius: 12px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px var(--shadow-light);
            height: 100%; /* Ensure cards fill height in grid */
        }
        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.1);
        }
        .custom-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            background-color: var(--white);
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .custom-card-header h5 {
            color: var(--dark-blue);
            font-weight: 600;
            margin-bottom: 0;
        }
        .custom-card-body {
            padding: 1.5rem;
        }

        /* Specific Info Card Styling */
        .info-card-icon {
            font-size: 2.5rem;
            color: var(--primary-blue);
            margin-bottom: 1rem;
        }
        .info-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-blue);
        }
        .info-card p {
            color: #6c757d;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        /* Table Styling */
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-custom {
            width: 100%;
            margin-bottom: 0;
            color: var(--text-color);
            min-width: 800px; /* Ensure horizontal scroll on smaller screens */
        }
        .table-custom thead th {
            background-color: var(--primary-blue);
            color: var(--white);
            border-color: var(--dark-blue);
            padding: 0.75rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            white-space: nowrap;
        }
        .table-custom tbody tr {
            background-color: var(--white);
        }
        .table-custom tbody tr:nth-of-type(even) {
            background-color: var(--light-gray);
        }
        .table-custom tbody tr:hover {
            background-color: #e9f2fb;
        }
        .table-custom tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.9rem;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-blue);
            display: block;
            margin: 0 auto;
        }
        .user-name-text {
            display: block;
            margin-top: 5px;
            font-size: 0.75rem;
            color: #555;
            text-align: center;
        }

        /* Status Badges - Using Bootstrap's .badge classes */
        .badge {
            padding: 0.5em 0.8em;
            border-radius: 0.375rem; /* Bootstrap's default for rounded-pill */
            font-weight: 600;
            font-size: 0.75em;
            text-transform: uppercase;
        }

        /* Custom Colors for badges if needed, otherwise Bootstrap's are fine */
        /* .badge-programado { background-color: #17a2b8; color: white; } */
        /* .badge-registro { background-color: #ffc107; color: #212529; } */
        /* .badge-abordando { background-color: #007bff; color: white; } */
        /* .badge-navegando { background-color: #28a745; color: white; } */
        /* .badge-arribado { background-color: #6c757d; color: white; } */
    </style>
</head>
<body>
<?php include('templates/navbar/navbar.php') ?>

<header class="hero-banner">
    <div class="container">
        <h1>Bienvenido al Panel de Control de MarTransport</h1>
        <p class="lead">Monitoreo simplificado de tus operaciones marítimas.</p>
    </div>
</header>

<div class="container py-4">
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="custom-card text-center p-4 info-card">
                <i class="fas fa-ship info-card-icon"></i>
                <h3 class="mb-2"><?php echo $total_embarcaciones_activas; ?></h3>
                <p>Embarcaciones Activas</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="custom-card text-center p-4 info-card">
                <i class="fas fa-route info-card-icon"></i>
                <h3 class="mb-2"><?php echo $total_rutas_proximas; ?></h3>
                <p>Rutas Próximas (24h)</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="custom-card text-center p-4 info-card">
                <i class="fas fa-users info-card-icon"></i>
                <h3 class="mb-2"><?php echo $total_usuarios; ?></h3>
                <p>Usuarios Registrados</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="custom-card">
                <div class="custom-card-header">
                    <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                    <h5>Rutas Próximas y Salidas (24 Horas)</h5>
                </div>
                <div class="custom-card-body p-0">
                    <div class="table-responsive-custom">
                        <?php if (!empty($rutas)): ?>
                            <table class="table table-striped table-hover table-custom">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Origen</th>
                                        <th>Destino</th>
                                        <th>Salida</th>
                                        <th>Llegada</th>
                                        <th>Barco #</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rutas as $ruta):
                                        $estado_viaje = obtenerEstadoViaje($ruta['fecha_origen'], $ruta['hora_salida']);
                                    ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php if (!empty($ruta['user_image_name'])): ?>
                                                    <img src="uploads/usuarios/<?php echo htmlspecialchars($ruta['user_image_name']); ?>" alt="Perfil de <?php echo htmlspecialchars($ruta['usuario']); ?>" class="user-avatar">
                                                <?php else: ?>
                                                    <img src="uploads/usuarios/default.png" alt="Perfil por defecto" class="user-avatar">
                                                <?php endif; ?>
                                                <span class="user-name-text"><?php echo htmlspecialchars($ruta['usuario']); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($ruta['origen']); ?></td>
                                            <td><?php echo htmlspecialchars($ruta['destino']); ?></td>
                                            <td><?php echo htmlspecialchars($ruta['fecha_origen']); ?> <br><small class="text-muted"><?php echo htmlspecialchars(substr($ruta['hora_salida'], 0, 5)); ?></small></td>
                                            <td><?php echo htmlspecialchars($ruta['fecha_destino']); ?> <br><small class="text-muted"><?php echo htmlspecialchars(substr($ruta['hora_llegada'], 0, 5)); ?></small></td>
                                            <td><?php echo htmlspecialchars($ruta['numero_barco']); ?></td>
                                            <td><?php echo htmlspecialchars($ruta['tipo_embarcacion']); ?></td>
                                            <td><span class="badge bg-<?php echo $estado_viaje['clase']; ?>"><?php echo $estado_viaje['estado']; ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <p class="lead">No hay rutas programadas en las próximas 24 horas.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>