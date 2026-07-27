<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: var(--azul-profundo);">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="fas fa-user-shield me-2"></i> <i class="fas fa-ship me-2"></i> Administración
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAdmin" aria-controls="navbarNavAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAdmin">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light" href="../cerrarSesion.php">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

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

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--blanco-espuma) !important;
    }

    .navbar-brand i {
        font-size: 1.2em; /* Ajusta el tamaño de los íconos del brand */
    }

    .navbar-nav .nav-link {
        color: var(--blanco-espuma) !important;
        margin-left: 15px;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .navbar-nav .nav-link.btn-outline-light:hover {
        background-color: var(--blanco-espuma);
        color: var(--azul-profundo) !important;
    }

    /* Media queries para responsividad */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background-color: var(--azul-profundo); /* Fondo para el menú colapsado */
            padding: 1rem;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            margin-top: 10px; /* Para separarlo de la barra principal */
        }
        .navbar-nav .nav-item {
            margin-bottom: 5px;
        }
        .navbar-nav .nav-link {
            width: 100%; /* Botón de cerrar sesión ocupa todo el ancho */
            text-align: center;
            margin-left: 0;
        }
    }
</style>