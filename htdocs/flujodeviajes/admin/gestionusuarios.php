<?php
session_start();


include('bd.php'); 

if (!(isset($_SESSION['rango']) && $_SESSION['rango'] == 'admin')) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

$total_usuarios_registrados = 0;
$sql_total_usuarios = "SELECT COUNT(*) as total FROM usuarios";
$result_total_usuarios = $conn->query($sql_total_usuarios);
if ($result_total_usuarios) {
    $row = $result_total_usuarios->fetch_assoc();
    $total_usuarios_registrados = $row['total'];
    $result_total_usuarios->free();
}

$conn->close(); 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - MarTransport Admin</title>
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
            align-items: center; 
            text-align: center; 
        }

        .marine-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(42, 122, 140, 0.18);
        }

        .marine-card .card-icon {
            font-size: 3.5rem;
            color: var(--azul-turquesa);
            margin-bottom: 1rem;
            margin-top: 1.5rem; 
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
            flex-grow: 1; 
        }

        .marine-card .btn {
            background-color: var(--azul-turquesa);
            border-color: var(--azul-turquesa);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease, border-color 0.3s ease;
            margin-bottom: 1.5rem; 
        }
        .marine-card .btn:hover {
            background-color: var(--azul-profundo);
            border-color: var(--azul-profundo);
        }

        .metric-card {
            background: linear-gradient(45deg, #e0f2f7, var(--blanco-espuma));
            border: 1px solid var(--azul-claro);
            padding: 2rem 1.5rem;
            margin-bottom: 3rem; 
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


        .alert {
            display: flex;
            align-items: center;
            padding: 0.55rem 0.65rem 0.55rem 0.75rem;
            border-radius: 1rem;
            min-width: 300px;
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
        .alert .content {
            display: flex;
            align-items: center;
        }
        .alert .icon {
            padding: 0.5rem;
            margin-right: 1rem;
            border-radius: 39% 61% 42% 58% / 50% 51% 49% 50%;
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

        .alert.success {
            background-color: rgba(62, 189, 97, 0.2);
            border: 2px solid #3ebd61;
            color: #333C48;
        }
        .alert.success .icon {
            background-color: #3ebd61;
        }
        .alert.success .icon svg path {
            fill: #fff;
        }

        .alert.danger {
            background-color: rgba(236, 77, 43, 0.2);
            border: 2px solid #EC4D2B;
            color: #333C48;
        }
        .alert.danger .icon {
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
            .hero-section {
                padding: 3rem 0 5rem;
            }
            .hero-section h1 {
                font-size: 2.5rem;
            }
            .marine-card {
                padding: 1.5rem;
            }
            .marine-card h3 {
                font-size: 1.5rem;
            }
            .marine-card p {
                font-size: 0.85rem;
            }
            .marine-card .btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
            .metric-card {
                padding: 1.5rem;
            }
            .metric-card .metric-value {
                font-size: 2.5rem;
            }
            .metric-card .metric-label {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<?php include('../templates/navbar/navbaradmin.php') ?>

<div class="hero-section">
    <div class="container">
        <h1>Gestión de Usuarios</h1>
        <p class="lead">Administra las cuentas de usuario, roles y permisos de la plataforma.</p>
        
    </div>
    <div class="nav-wave"></div>
</div>

<div class="container py-5">

    <div id="alert-container" class="position-relative" style="min-height: 50px;">
        <?php
   
        ?>
    </div>


    <h2 class="text-center mb-5 fw-bold" style="color: var(--azul-profundo);">Acciones de Usuarios</h2>
    <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="marine-card p-4">
                <i class="fa-solid fa-user-group card-icon"></i> <h3>Ver Todos los Usuarios</h3>
                <p>Accede a la lista completa de usuarios registrados en el sistema.</p>
                <a href="consulta.php" class="btn btn-primary">Consultar Usuarios</a>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="marine-card p-4">
                <i class="fa-solid fa-user-gear card-icon"></i> <h3>Administrar Usuarios</h3>
                <p>Agrega nuevos usuarios, edita perfiles existentes o elimina cuentas.</p>
                <a href="gestion.php" class="btn btn-primary">Gestionar Usuarios</a>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="marine-card p-4">
                <i class="fa-solid fa-image-portrait card-icon"></i> <h3>Gestionar Avatares</h3>
                <p>Administra las imágenes de perfil por defecto o personalizadas de los usuarios.</p>
                <a href="logo.php" class="btn btn-primary">Administrar Avatares</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>