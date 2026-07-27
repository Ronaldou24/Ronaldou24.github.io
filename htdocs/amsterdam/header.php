<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? SITE_NAME) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDesc ?? 'AMS Swimwear – Puerto Vallarta') ?>">
    <link rel="icon" href="<?= SITE_URL ?>/assets/img/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/main.css">
    <?= $extraHead ?? '' ?>
</head>
<body>

<header class="site-header" id="header">
    <div class="header-inner">
        <button class="nav-toggle" id="navToggle" aria-label="Menú">
            <span></span><span></span><span></span>
        </button>

        <a href="<?= SITE_URL ?>" class="logo">
            <img src="<?= SITE_URL ?>/assets/img/logo.png" alt="AMS Swimwear">
        </a>

        <div class="header-actions">
            <button class="btn-search" id="btnSearch" aria-label="Buscar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
            </button>
            <a href="<?= SITE_URL ?>/carrito.php" class="btn-cart" aria-label="Carrito">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <span class="cart-count" id="cartCount">0</span>
            </a>
        </div>
    </div>

    <!-- Barra de búsqueda -->
    <div class="search-bar" id="searchBar">
        <div class="search-inner">
            <input type="text" id="searchInput" placeholder="Buscar productos..." autocomplete="off">
            <div class="search-results" id="searchResults"></div>
        </div>
        <button class="search-close" id="searchClose">✕</button>
    </div>

    <!-- Navegación categorías -->
    <nav class="cat-nav" id="catNav">
        <ul>
            <li><a href="<?= SITE_URL ?>/" class="<?= !isset($_GET['categoria']) ? 'active' : '' ?>">Todos</a></li>
            <?php
            $db = getDB();
            $cats = $db->query("SELECT * FROM categorias WHERE activa=1 ORDER BY orden")->fetchAll();
            foreach ($cats as $cat):
            ?>
            <li>
                <a href="<?= SITE_URL ?>/?categoria=<?= $cat['slug'] ?>"
                   class="<?= ($_GET['categoria'] ?? '') === $cat['slug'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['nombre']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>

<main class="main-content">