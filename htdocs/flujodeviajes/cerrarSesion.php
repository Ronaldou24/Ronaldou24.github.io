<?php
session_start();

// Definir las URLs a las que redirigir
$login_page = 'login.php'; // Página de inicio de sesión
$dashboard_page = 'index.php'; // Página a la que redirigir si se cancela (ej. dashboard, inicio)

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'confirm') {
        // Destruir todas las variables de sesión.
        session_unset();

        // Destruir la sesión.
        session_destroy();

        // Redirigir al usuario a la página de login.
        header("Location: " . $login_page);
        exit();
    } elseif ($_GET['action'] === 'cancel') {
        // Si el usuario cancela, redirigir a la página principal o dashboard.
        header("Location: " . $dashboard_page);
        exit();
    }
}
// Si no hay acción específica o es la primera carga de la página, mostrar el modal de confirmación.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrar Sesión - MarTransport</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* Variables de estilo de MarTransport (Asegúrate de que sean consistentes en toda tu app) */
        :root {
            --azul-profundo: #1a4a5a;
            --azul-turquesa: #2a7a8c;
            --azul-claro: #8fc7d8;
            --blanco-espuma: #f0f8ff;
            --degradado-marino: linear-gradient(135deg, var(--azul-profundo), var(--azul-turquesa));
            --shadow-light: rgba(0, 0, 0, 0.08);
            --text-dark: #343a40;
            --danger-red: #dc3545;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--gris-claro); /* Usar gris claro de tu paleta */
            color: var(--text-dark);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Ocupar toda la altura de la ventana */
            margin: 0;
            background-image: var(--degradado-marino); /* Fondo con degradado marino */
            animation: fadeInBackground 1s ease-out forwards;
        }

        .logout-container {
            background-color: var(--blanco-espuma);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow-light); /* Sombra más pronunciada */
            text-align: center;
            max-width: 500px;
            width: 90%;
            border: 1px solid rgba(26, 74, 90, 0.05);
            animation: fadeInScale 0.6s ease-out forwards; /* Animación de entrada */
        }

        .logout-container h1 {
            color: var(--azul-profundo);
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 2.5rem;
        }

        .logout-container p {
            font-size: 1.1rem;
            color: var(--text-dark);
            margin-bottom: 35px;
        }

        .logout-container .icon {
            font-size: 4rem; /* Tamaño del icono */
            color: var(--danger-red); /* Color del icono de advertencia/salida */
            margin-bottom: 30px;
            animation: bounceIn 0.8s ease-out forwards; /* Animación para el icono */
        }

        .btn-group-custom {
            display: flex;
            justify-content: center;
            gap: 20px; /* Espacio entre botones */
            flex-wrap: wrap; /* Para responsividad */
        }

        .btn-logout-confirm,
        .btn-logout-cancel {
            padding: 14px 30px;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none; /* Quita el subrayado de los enlaces */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-logout-confirm {
            background-color: var(--danger-red);
            border: 2px solid var(--danger-red);
            color: var(--blanco-espuma);
        }

        .btn-logout-confirm:hover {
            background-color: #c82333; /* Rojo un poco más oscuro */
            border-color: #bd2130;
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(220, 53, 69, 0.4);
            color: var(--blanco-espuma); /* Asegura que el texto siga blanco */
        }

        .btn-logout-cancel {
            background-color: var(--blanco-espuma);
            border: 2px solid var(--azul-turquesa); /* Borde azul turquesa */
            color: var(--azul-profundo);
        }

        .btn-logout-cancel:hover {
            background-color: var(--azul-turquesa);
            border-color: var(--azul-turquesa);
            color: var(--blanco-espuma);
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(42, 122, 140, 0.3);
        }

        .btn-logout-confirm i,
        .btn-logout-cancel i {
            margin-right: 10px;
        }

        /* Animaciones */
        @keyframes fadeInBackground {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes bounceIn {
            0% { transform: scale(0.1); opacity: 0; }
            60% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }

        /* Media Queries para responsividad */
        @media (max-width: 768px) {
            .logout-container {
                padding: 30px;
            }
            .logout-container h1 {
                font-size: 2rem;
            }
            .logout-container p {
                font-size: 1rem;
            }
            .btn-group-custom {
                flex-direction: column; /* Apila los botones en pantallas pequeñas */
                gap: 15px;
            }
            .btn-logout-confirm,
            .btn-logout-cancel {
                width: 100%; /* Botones de ancho completo */
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h1>¿Cerrar Sesión?</h1>
        <p>Estás a punto de cerrar tu sesión actual. ¿Estás seguro de que quieres continuar?</p>
        <div class="btn-group-custom">
            <a href="?action=confirm" class="btn btn-logout-confirm">
                <i class="fas fa-check-circle"></i> Sí, cerrar sesión
            </a>
            <a href="?action=cancel" class="btn btn-logout-cancel">
                <i class="fas fa-times-circle"></i> Cancelar
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>