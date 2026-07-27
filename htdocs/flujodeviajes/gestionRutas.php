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
    <title>Gestión de Rutas - MarTransport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /*
         * GLOBAL STYLES & VARIABLES (from your provided navbar CSS)
         * IMPORTANT: Keep these in sync with your actual navbar.php if it's external.
         */
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@400;600;700&display=swap');

        :root {
            --primary-blue: #007bff; /* Vibrant Blue */
            --light-blue: #e7f3ff; /* Very light blue for subtle accents */
            --dark-blue: #0056b3; /* Darker blue for hover/active */
            --text-color: #333;
            --light-gray: #f8f9fa; /* For body background */
            --white: #ffffff;
            --shadow-light: rgba(0, 0, 0, 0.08);
            --gradient-light: linear-gradient(90deg, #e0f2f7 0%, #d4eaf0 100%); /* Very subtle gradient */
            --success-color: #28a745; /* Standard green for success */
            --danger-color: #dc3545; /* Standard red for danger */
        }

        body {
            font-family: 'Poppins', sans-serif; /* A bit more modern than Nunito */
            padding-top: 70px; /* Adjust based on navbar height */
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh; /* Ensure body takes full viewport height */
            display: flex;
            flex-direction: column;
        }

        /* Navbar Main Styling - COPIED FROM YOUR PROVIDED CSS */
        .navbar-mainbg {
            background-color: var(--white); /* Clean white background */
            box-shadow: 0 4px 12px var(--shadow-light); /* Softer, more modern shadow */
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.3s ease-in-out;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05); /* Subtle bottom border */
        }

        .navbar-brand {
            color: var(--dark-blue) !important; /* Make brand color darker blue for contrast */
            font-weight: 700;
            letter-spacing: 0.5px; /* Slightly less aggressive spacing */
            font-size: 1.6rem; /* Slightly larger brand text */
            display: flex;
            align-items: center;
            cursor: pointer; /* Add cursor pointer to indicate interactivity */
        }
        /* Specific styles for the logo when it acts as a toggler on mobile */
        @media (max-width: 991px) {
            .navbar-brand.toggler-logo {
                pointer-events: auto; /* Ensure it's clickable */
                cursor: pointer;
            }
        }
        @media (min-width: 992px) {
            .navbar-brand.toggler-logo {
                pointer-events: auto; /* Ensure it's clickable */
                cursor: pointer;
            }
        }

        .navbar-brand i {
            margin-right: 8px; /* Adjusted margin */
            font-size: 1.8rem; /* Slightly larger icon */
            color: var(--primary-blue); /* Vibrant blue for the icon */
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover i {
            transform: scale(1.1); /* Subtle zoom instead of rotation */
        }

        /* Nav Links */
        #navbarSupportedContent ul li a {
            color: var(--text-color); /* Darker text for better readability */
            padding: 15px 20px; /* Adjusted padding for a modern feel */
            transition: all 0.3s ease;
            position: relative;
            font-weight: 600; /* Slightly bolder */
            font-size: 0.95rem;
            display: flex; /* For icon and text alignment */
            align-items: center;
        }

        #navbarSupportedContent ul li a i {
            margin-right: 8px; /* Space between icon and text */
            color: var(--primary-blue); /* Icon color matches brand icon */
            font-size: 1.1rem;
        }

        #navbarSupportedContent ul li a:hover {
            color: var(--primary-blue); /* Vibrant blue on hover */
            background-color: var(--light-blue); /* Light blue background on hover */
            transform: none; /* No translateY for a cleaner hover */
        }

        #navbarSupportedContent > ul > li.active > a {
            color: var(--primary-blue); /* Active link is vibrant blue */
            font-weight: 700;
            background-color: var(--light-blue); /* Active link has light blue background */
        }

        /* Horizontal Selector (Active Indicator) */
        .hori-selector {
            background-color: var(--primary-blue); /* Vibrant blue for the active indicator */
            height: 4px; /* Slightly thicker */
            bottom: 0;
            position: absolute;
            transition: all 0.3s ease;
            border-radius: 2px; /* Slightly rounded for softness */
            opacity: 0.8; /* A bit transparent */
        }

        @media (min-width: 992px) {
            .hori-selector {
                width: 100% !important;
                left: 0 !important;
                top: auto !important;
            }
            .navbar-nav {
                align-items: center; /* Vertically align items in desktop */
            }
            /* Hide the hamburger button on desktop */
            .navbar-toggler {
                display: none !important;
            }
        }

        /* Mobile Menu */
        @media (max-width: 991px) {
            .navbar-collapse {
                position: fixed;
                left: -320px; /* Wider mobile menu */
                top: 0; /* Full height from top */
                width: 320px; /* Fixed width */
                height: 100vh; /* Full viewport height */
                background: var(--dark-blue); /* Darker blue for mobile menu background */
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                overflow-y: auto;
                z-index: 9999; /* Higher z-index for mobile menu */
                padding-top: 20px; /* Space from top */
                box-shadow: 4px 0 15px rgba(0,0,0,0.2);
            }

            .navbar-collapse.show {
                left: 0;
            }

            .mobile-menu-overlay {
                position: fixed;
                top: 0; /* Full screen overlay */
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5); /* Darker overlay */
                backdrop-filter: blur(5px); /* More blur */
                visibility: hidden;
                opacity: 0;
                transition: all 0.4s ease;
                z-index: 9998;
            }

            .mobile-menu-overlay.active {
                visibility: visible;
                opacity: 1;
            }

            .btn-close-menu {
                position: absolute;
                right: 20px;
                top: 20px;
                color: var(--white); /* White close button */
                font-size: 1.8rem; /* Larger close icon */
                background: transparent;
                border: none;
                transition: transform 0.3s ease;
                z-index: 10000; /* Ensure close button is on top */
            }

            .btn-close-menu:hover {
                transform: rotate(90deg) scale(1.1); /* Enhanced rotation and zoom */
                color: var(--primary-blue); /* Highlight on hover */
            }

            #navbarSupportedContent ul li a {
                padding: 18px 25px; /* More generous padding for mobile links */
                border-bottom: 1px solid rgba(255,255,255,0.15); /* Slightly stronger separator */
                color: var(--white); /* White text in mobile menu */
                font-weight: 600;
            }

            #navbarSupportedContent ul li a i {
                color: var(--light-blue); /* Lighter icons in mobile menu */
            }

            #navbarSupportedContent ul li a:hover {
                background-color: var(--primary-blue); /* Primary blue background on hover */
                color: var(--white);
            }
            #navbarSupportedContent > ul > li.active > a {
                background-color: var(--primary-blue); /* Active link in mobile */
                color: var(--white);
            }
        }

        /* Toggler Icon (Hamburger) */
        .navbar-toggler {
            border: none;
            padding: 0.5rem; /* Smaller padding */
            transition: all 0.3s ease;
        }

        .navbar-toggler i {
            color: var(--primary-blue); /* Vibrant blue for hamburger icon */
            font-size: 1.8rem; /* Larger icon */
            transition: transform 0.3s ease;
        }

        .navbar-toggler:hover i {
            transform: scale(1.1); /* Subtle zoom on hover */
        }

        /* Custom Logout Button (for better visual separation) */
        .nav-item.logout-item a {
            background-color: var(--primary-blue);
            color: var(--white) !important;
            border-radius: 5px;
            padding: 10px 20px;
            margin-left: 15px; /* Space from other nav items */
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 123, 255, 0.3); /* Subtle button shadow */
        }
        .nav-item.logout-item a:hover {
            background-color: var(--dark-blue);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px); /* Lift on hover */
            color: var(--white) !important; /* Ensure text remains white */
        }
        .nav-item.logout-item a i {
            color: var(--white) !important; /* Ensure icon remains white */
        }

        /* Adjustments for desktop logout button in mobile view */
        @media (max-width: 991px) {
            .nav-item.logout-item a {
                background-color: transparent; /* No background color in mobile menu */
                color: var(--white) !important; /* White text */
                box-shadow: none; /* No shadow */
                margin-left: 0;
                border-radius: 0;
                padding: 18px 25px; /* Match other mobile links */
                border-bottom: 1px solid rgba(255,255,255,0.15);
            }
            .nav-item.logout-item a i {
                color: var(--light-blue) !important; /* Lighter icon */
            }
            .nav-item.logout-item a:hover {
                background-color: var(--primary-blue);
                transform: none; /* No lift */
            }
        }

        /*
         * PAGE SPECIFIC STYLES (for gestionRutas.php)
         */

        /* Hero Section for internal pages */
        .page-hero-section {
            background: linear-gradient(90deg, var(--light-blue) 0%, var(--white) 100%); /* Lighter, subtle gradient */
            padding: 3rem 0;
            color: var(--dark-blue); /* Darker text on light background */
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); /* Subtle shadow */
            margin-bottom: 3rem; /* Space below the hero section */
        }

        .page-hero-section h1 {
            font-weight: 700;
            font-size: 2.5rem;
            color: var(--dark-blue);
        }
        .page-hero-section p.lead {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        /* Main content area flexibility */
        .main-content {
            flex-grow: 1; /* Allow content to grow and push footer (if any) down */
            padding-bottom: 3rem; /* Add some space at the bottom */
        }

        /* Card Styles */
        .btn-card {
            background: var(--white);
            border-radius: 12px; /* Softly rounded corners */
            transition: all 0.3s ease;
            border: none; /* Remove default border */
            box-shadow: 0 4px 15px var(--shadow-light); /* Modern, soft shadow */
            text-decoration: none !important;
            color: var(--text-color); /* Default text color */
            display: block; /* Ensure the whole card is clickable */
            height: 100%; /* Make cards equal height in a row */
        }

        .btn-card:hover {
            transform: translateY(-8px); /* More pronounced lift on hover */
            box-shadow: 0 10px 30px rgba(0, 123, 255, 0.15); /* Blue-tinted shadow on hover */
            color: var(--primary-blue); /* Text color changes to primary blue on hover */
        }

        .btn-card .card-body {
            padding: 2rem; /* Generous padding inside cards */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .btn-card .icon-container {
            border-radius: 50%; /* Make them perfectly round */
            padding: 25px; /* More padding for a generous circle */
            margin-bottom: 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 90px; /* Larger circle */
            height: 90px; /* Larger circle */
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow on icon container */
        }

        /* Specific icon container colors using standard Bootstrap classes and overriding */
        .btn-card .icon-container.bg-success {
            background-color: var(--success-color) !important; /* Green for add */
        }
        .btn-card .icon-container.bg-danger {
            background-color: var(--danger-color) !important; /* Red for delete/edit */
        }

        .btn-card:hover .icon-container.bg-success {
            background-color: #218838 !important; /* Slightly darker green on hover */
            transform: scale(1.05);
        }
        .btn-card:hover .icon-container.bg-danger {
            background-color: #c82333 !important; /* Slightly darker red on hover */
            transform: scale(1.05);
        }

        .btn-card .icon-container i {
            color: var(--white); /* White icons inside the colored circles */
            font-size: 2.8rem; /* Larger icons */
            transition: transform 0.3s ease;
        }
        .btn-card:hover .icon-container i {
            transform: rotate(5deg); /* Subtle rotation on hover */
        }

        .btn-card .card-title {
            color: var(--dark-blue); /* Default title color */
            font-weight: 600;
            font-size: 1.4rem;
            margin-bottom: 0;
            transition: color 0.3s ease;
        }
        .btn-card:hover .card-title {
            color: var(--primary-blue); /* Title color changes on card hover */
        }

        /* Responsive adjustments for cards */
        @media (max-width: 767px) {
            .col-md-6.mb-4 { /* Targets the cards on small screens */
                margin-bottom: 1.5rem !important; /* Reduce margin for better mobile spacing */
            }
            .btn-card .card-title {
                font-size: 1.2rem; /* Smaller title on mobile */
            }
        }
    </style>
</head>
<body>
<?php include('templates/navbar/navbar.php') ?>

<div class="page-hero-section text-center">
    <div class="container">
        <h1 class="mb-2">Gestión de Rutas</h1>
        <p class="lead">Administra y organiza tus rutas de transporte marino.</p>
    </div>
</div>

<div class="container main-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <a href="./funciones/agregarRuta.php" class="card btn-card text-decoration-none">
                        <div class="card-body text-center">
                            <div class="icon-container bg-success mb-3">
                                <i class="fas fa-plus-circle text-white"></i>
                            </div>
                            <h5 class="card-title">Agregar Nueva Ruta</h5>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 mb-4">
                    <a href="./funciones/consultaRuta.php" class="card btn-card text-decoration-none">
                        <div class="card-body text-center">
                            <div class="icon-container bg-danger mb-3">
                                <i class="fas fa-trash-alt text-white"></i>
                            </div>
                            <h5 class="card-title">Eliminar / Editar Ruta</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

</script>

</body>
</html>