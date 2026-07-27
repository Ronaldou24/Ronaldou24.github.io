<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarTransport - Sistema de Transporte Marino</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
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
        }

        body {
            font-family: 'Poppins', sans-serif; /* A bit more modern than Nunito */
            padding-top: 70px; /* Adjust based on navbar height */
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Navbar Main Styling */
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
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-mainbg">
    <div class="container-fluid">
        <a class="navbar-brand toggler-logo" href="index.php">
            <i class="fas fa-ship"></i>MarTransport
        </a>

        <button class="navbar-toggler d-lg-none" type="button" id="mobileMenuButton" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <button class="btn-close-menu d-lg-none" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>

            <ul class="navbar-nav ms-auto">
                <div class="hori-selector"></div>

                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="gestionRutas.php">
                        <i class="fas fa-route"></i> Gestión de Rutas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="operaciones.php">
                        <i class="fas fa-anchor"></i> Operaciones
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="pantalla.php">
                        <i class="fas fa-desktop"></i> Pantalla
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="perfil.php">
                        <i class="fas fa-user-circle"></i> Perfil
                    </a>
                </li>

                <li class="nav-item logout-item">
                    <a class="nav-link" href="cerrarSesion.php" id="logoutLink">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="mobile-menu-overlay"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).ready(function() {
    const mobileMenuButton = $('#mobileMenuButton');
    const navbarCollapse = $('#navbarSupportedContent');
    const overlay = $('.mobile-menu-overlay');
    const closeButton = $('.btn-close-menu');
    const logoToggler = $('.navbar-brand.toggler-logo'); // Get the logo element

    // Function to toggle menu
    const toggleMenu = () => {
        navbarCollapse.toggleClass('show');
        overlay.toggleClass('active');
        $('body').toggleClass('overflow-hidden'); // Prevent scrolling body when menu is open
        // Update aria-expanded for accessibility
        const isExpanded = navbarCollapse.hasClass('show');
        mobileMenuButton.attr('aria-expanded', isExpanded);
    }

    // Event Listeners
    mobileMenuButton.click((e) => {
        e.stopPropagation();
        toggleMenu();
    });

    closeButton.click(toggleMenu);
    overlay.click(toggleMenu);

    $(document).keyup((e) => {
        if (e.key === "Escape" && navbarCollapse.hasClass('show')) {
            toggleMenu();
        }
    });

    // Close menu when a nav link is clicked (only on mobile), except for logout
    $('.navbar-nav .nav-link').not('#logoutLink').click(function() {
        if ($(window).width() < 992) { // Only on mobile screens
            // Delay toggle slightly to allow link navigation first
            setTimeout(toggleMenu, 150);
        }
    });

    // Handle logout link click separately with SweetAlert2
    $('#logoutLink').click(function(e) {
        e.preventDefault(); // Prevent default navigation

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Estás a punto de cerrar tu sesión.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007bff', // Match your primary blue
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = $(this).attr('href'); // Redirect if user confirms
            }
        });
    });

    // Make the logo also toggle the menu on small screens
    logoToggler.click(function(e) {
        // Only toggle if on a small screen (<= 991px)
        if ($(window).width() <= 991) {
            e.preventDefault(); // Prevent default link behavior
            toggleMenu();
        }
        // If on desktop, let the default link behavior (go to index.php) happen
    });


    // Function to update the horizontal selector (active indicator)
    const updateSelector = () => {
        const activeItem = $('.nav-item.active');
        const selector = $('.hori-selector');

        if(activeItem.length && $(window).width() > 991) {
            const position = activeItem.position();
            selector.css({
                'width': activeItem.outerWidth() + 'px',
                'left': position.left + 'px'
            });
            selector.show(); // Ensure it's visible on desktop
        } else { // Handle mobile or no active item on desktop
            selector.hide(); // Hide selector on mobile or if no active item
        }
    }

    // Function to set the active link based on current page
    const setActiveLink = () => {
        const currentPage = window.location.pathname.split("/").pop();
        let foundActive = false;
        $('.navbar-nav .nav-item').removeClass('active'); // Remove active from all items

        $('.nav-link').each(function() {
            const linkHref = $(this).attr('href');
            // Check if current page matches link href, or if it's the root and link is index.php
            if (linkHref === currentPage || (currentPage === '' && linkHref === 'index.php')) {
                $(this).parent().addClass('active');
                foundActive = true;
            }
        });

        // Fallback: If no specific link matches (e.g., if on a sub-page not in nav), default "Inicio" to active
        if (!foundActive) {
            $('.nav-item a[href="index.php"]').parent().addClass('active');
        }
        updateSelector(); // Update selector after active link is set
    };

    // Initialize on document ready
    setActiveLink();
    $(window).on('resize', function() {
        setActiveLink(); // Recalculate and update on resize
    });
});
</script>

</body>
</html>