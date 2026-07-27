<?php
session_start();

if (!(isset($_SESSION['rango']) && $_SESSION['rango'] == 'admin')) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}


include('bd.php'); 


$total_rutas = 0;
$sql_total_rutas = "SELECT COUNT(*) as total FROM rutas";
$result_total_rutas = $conn->query($sql_total_rutas);
if ($result_total_rutas) {
    $row = $result_total_rutas->fetch_assoc();
    $total_rutas = $row['total'];
    $result_total_rutas->free();
}


$rutas_en_navegacion = 0;
$current_datetime_str = (new DateTime())->format('Y-m-d H:i:s');
$sql_navegacion = "SELECT COUNT(*) as en_navegacion FROM rutas WHERE STR_TO_DATE(CONCAT(fecha_origen, ' ', hora_salida), '%Y-%m-%d %H:%i:%s') <= ? AND STR_TO_DATE(CONCAT(fecha_destino, ' ', hora_llegada), '%Y-%m-%d %H:%i:%s') > ?";
$stmt_navegacion = $conn->prepare($sql_navegacion);
if ($stmt_navegacion) {
    $stmt_navegacion->bind_param("ss", $current_datetime_str, $current_datetime_str);
    $stmt_navegacion->execute();
    $result_navegacion = $stmt_navegacion->get_result();
    if ($result_navegacion) {
        $row = $result_navegacion->fetch_assoc();
        $rutas_en_navegacion = $row['en_navegacion'];
        $result_navegacion->free();
    }
    $stmt_navegacion->close();
}


$total_usuarios = 0;
$sql_total_usuarios = "SELECT COUNT(*) as total FROM usuarios";
$result_total_usuarios = $conn->query($sql_total_usuarios);
if ($result_total_usuarios) {
    $row = $result_total_usuarios->fetch_assoc();
    $total_usuarios = $row['total'];
    $result_total_usuarios->free();
}


$tipos_embarcacion_unicos = 0;
$sql_tipos_unicos = "SELECT COUNT(DISTINCT tipo_embarcacion) as unicos FROM rutas";
$result_tipos_unicos = $conn->query($sql_tipos_unicos);
if ($result_tipos_unicos) {
    $row = $result_tipos_unicos->fetch_assoc();
    $tipos_embarcacion_unicos = $row['unicos'];
    $result_tipos_unicos->free();
}


$conn->close(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - MarTransport</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --azul-profundo: #1a4a5a;
            --azul-turquesa: #2a7a8c;
            --azul-claro: #8fc7d8;
            --blanco-espuma: #f0f8ff;
            --degradado-marino: linear-gradient(135deg, var(--azul-profundo), var(--azul-turquesa));
            --shadow-light: rgba(0, 0, 0, 0.08);
            --text-dark: #343a40;
        }

        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 70px; 
            background-color: var(--blanco-espuma);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .hero-section {
            background: var(--degradado-marino);
            padding: 4rem 0 6rem; 
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-bottom-left-radius: 25px; 
            border-bottom-right-radius: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 3rem;
        }

        .hero-section h1 {
            font-weight: 700;
            font-size: 3rem; 
        }
        .hero-section p.lead {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .nav-wave {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 100px; 
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg"><path fill="%23f0f8ff" fill-opacity="1" d="M0,160L48,149.3C96,139,192,117,288,128C384,139,480,181,576,176C672,171,768,117,864,101.3C960,85,1056,107,1152,122.7C1248,139,1344,149,1392,154.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat center bottom;
            background-size: cover;
        }

        .marine-card {
            background: white;
            border-radius: 15px;
            transition: all 0.3s ease;
            border: none; 
            box-shadow: 0 4px 15px var(--shadow-light);
            height: 100%; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .marine-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(42, 122, 140, 0.18);
        }

        .marine-card .card-icon {
            font-size: 3.5rem; 
            color: var(--azul-turquesa);
            margin-bottom: 1rem;
            display: block; 
        }
        .marine-card h3 {
            color: var(--azul-profundo);
            font-weight: 600;
            font-size: 1.8rem;
            margin-bottom: 0.75rem;
        }
        .marine-card p {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .marine-card .btn {
            background-color: var(--azul-turquesa);
            border-color: var(--azul-turquesa);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .marine-card .btn:hover {
            background-color: var(--azul-profundo);
            border-color: var(--azul-profundo);
        }

        .metric-card {
            text-align: center;
            background: linear-gradient(45deg, #e0f2f7, var(--blanco-espuma));
            border: 1px solid var(--azul-claro);
            padding: 2.5rem 1.5rem; 
        }
        .metric-card .metric-value {
            font-size: 3.5rem; 
            font-weight: 700;
            color: var(--azul-profundo);
            margin-bottom: 0.5rem;
        }
        .metric-card .metric-label {
            font-size: 1.1rem;
            color: var(--azul-turquesa);
            font-weight: 600;
        }
        .metric-card .fa-solid {
            font-size: 3rem;
            color: var(--azul-turquesa);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<?php include('../templates/navbar/navbaradmin.php') ?>

<div class="hero-section">
    <div class="container">
        <h1>Panel de Administración</h1>
        <p class="lead">Bienvenido, Administrador <?php echo htmlspecialchars($_SESSION['usuario'] ?? ''); ?>. Aquí puedes gestionar el sistema y ver métricas clave.</p>
    </div>
    <div class="nav-wave"></div>
</div>

<div class="container py-5">
    <h2 class="text-center mb-5 fw-bold" style="color: var(--azul-profundo);">Métricas Clave del Sistema</h2>
    <div class="row g-4 mb-5 justify-content-center">
        <div class="col-md-6 col-lg-3">
            <div class="marine-card metric-card">
                <i class="fa-solid fa-route"></i>
                <div class="metric-value"><?php echo $total_rutas; ?></div>
                <div class="metric-label">Rutas Registradas</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="marine-card metric-card">
                <i class="fa-solid fa-ship"></i>
                <div class="metric-value"><?php echo $rutas_en_navegacion; ?></div>
                <div class="metric-label">Rutas en Navegación</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="marine-card metric-card">
                <i class="fa-solid fa-users"></i>
                <div class="metric-value"><?php echo $total_usuarios; ?></div>
                <div class="metric-label">Usuarios Registrados</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="marine-card metric-card">
                <i class="fa-solid fa-sailboat"></i>
                <div class="metric-value"><?php echo $tipos_embarcacion_unicos; ?></div>
                <div class="metric-label">Tipos de Embarcación</div>
            </div>
        </div>
    </div>

    
    <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="marine-card p-4 text-center">
                <i class="fas fa-users card-icon"></i>
                <h3>Gestión de Usuarios</h3>
                <p>Administra las cuentas de usuario, roles y permisos.</p>
                <a href="gestionusuarios.php" class="btn btn-primary mt-auto">Ir a Gestión de Usuarios</a>
            </div>
        </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>