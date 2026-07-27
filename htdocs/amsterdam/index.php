<?php
require_once __DIR__ . '/db.php';

$db = getDB();
$pageTitle = 'AMS Swimwear – Catálogo';

// ---- Filtros ----
$categoria = $_GET['categoria'] ?? '';
$busqueda  = trim($_GET['q'] ?? '');
$orden     = $_GET['orden'] ?? 'nombre';
$pagina    = max(1, (int)($_GET['p'] ?? 1));
$porPagina = 16;

// ---- Query dinámica ----
$where = ["p.estado = 'activo'"];
$params = [];

if ($categoria) {
    $where[] = "c.slug = :slug";
    $params[':slug'] = $categoria;
}

if ($busqueda) {
    $where[] = "(p.nombre LIKE :q OR p.descripcion LIKE :q2)";
    $params[':q']  = "%$busqueda%";
    $params[':q2'] = "%$busqueda%";
}

$whereSQL = 'WHERE ' . implode(' AND ', $where);

$ordenMap = [
    'nombre'      => 'p.nombre ASC',
    'precio_asc'  => 'p.precio ASC',
    'precio_desc' => 'p.precio DESC',
    'nuevo'       => 'p.nuevo DESC, p.created_at DESC',
    'destacado'   => 'p.destacado DESC',
];
$orderSQL = 'ORDER BY ' . ($ordenMap[$orden] ?? $ordenMap['nombre']);

// Total de resultados
$stmtTotal = $db->prepare("
    SELECT COUNT(*) FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    $whereSQL
");
$stmtTotal->execute($params);
$totalProductos = $stmtTotal->fetchColumn();
$totalPaginas   = ceil($totalProductos / $porPagina);
$offset         = ($pagina - 1) * $porPagina;

// Productos
$stmtProd = $db->prepare("
    SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    $whereSQL
    $orderSQL
    LIMIT :limit OFFSET :offset
");
foreach ($params as $k => $v) $stmtProd->bindValue($k, $v);
$stmtProd->bindValue(':limit',  $porPagina, PDO::PARAM_INT);
$stmtProd->bindValue(':offset', $offset,    PDO::PARAM_INT);
$stmtProd->execute();
$productos = $stmtProd->fetchAll();

// Categoría activa (para título)
$catActual = null;
if ($categoria) {
    $catActual = $db->prepare("SELECT * FROM categorias WHERE slug = ?");
    $catActual->execute([$categoria]);
    $catActual = $catActual->fetch();
}

include __DIR__ . '/includes/header.php';
?>

<section class="catalog-hero">
    <?php if ($catActual): ?>
        <h1><?= htmlspecialchars($catActual['nombre']) ?></h1>
    <?php elseif ($busqueda): ?>
        <h1>Resultados para "<em><?= htmlspecialchars($busqueda) ?></em>"</h1>
    <?php else: ?>
        <h1>Todos los Productos</h1>
    <?php endif; ?>
    <p class="results-count"><?= $totalProductos ?> productos encontrados</p>
</section>

<section class="catalog-section">
    <!-- Sidebar de filtros -->
    <aside class="filters-panel" id="filtersPanel">
        <div class="filters-header">
            <h3>Filtros</h3>
            <button class="filters-close" id="filtersClose">✕</button>
        </div>

        <form method="GET" id="filterForm">
            <?php if ($categoria): ?>
                <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
            <?php endif; ?>

            <div class="filter-group">
                <label>Buscar</label>
                <input type="text" name="q" value="<?= htmlspecialchars($busqueda) ?>" placeholder="Nombre del producto...">
            </div>

            <div class="filter-group">
                <label>Ordenar por</label>
                <select name="orden">
                    <option value="nombre"      <?= $orden === 'nombre'      ? 'selected' : '' ?>>Nombre A–Z</option>
                    <option value="precio_asc"  <?= $orden === 'precio_asc'  ? 'selected' : '' ?>>Precio: menor a mayor</option>
                    <option value="precio_desc" <?= $orden === 'precio_desc' ? 'selected' : '' ?>>Precio: mayor a menor</option>
                    <option value="nuevo"       <?= $orden === 'nuevo'       ? 'selected' : '' ?>>Más nuevos</option>
                    <option value="destacado"   <?= $orden === 'destacado'   ? 'selected' : '' ?>>Destacados</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Precio máximo</label>
                <input type="range" name="precio_max" min="0" max="2000" step="50"
                       value="<?= (int)($_GET['precio_max'] ?? 2000) ?>"
                       oninput="document.getElementById('precioVal').textContent = '$'+this.value">
                <span id="precioVal">$<?= (int)($_GET['precio_max'] ?? 2000) ?></span>
            </div>

            <button type="submit" class="btn-primary btn-block">Aplicar filtros</button>
            <a href="<?= SITE_URL ?>/" class="btn-ghost btn-block">Limpiar</a>
        </form>
    </aside>

    <!-- Grid de productos -->
    <div class="catalog-main">
        <div class="catalog-toolbar">
            <button class="btn-filters-toggle" id="btnFilters">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="4" y1="6" x2="11" y2="6"/><line x1="13" y1="6" x2="20" y2="6"/>
                    <line x1="4" y1="12" x2="7" y2="12"/><line x1="9" y1="12" x2="20" y2="12"/>
                    <line x1="4" y1="18" x2="13" y2="18"/><line x1="15" y1="18" x2="20" y2="18"/>
                    <circle cx="12" cy="6" r="1.5" fill="currentColor"/>
                    <circle cx="8" cy="12" r="1.5" fill="currentColor"/>
                    <circle cx="14" cy="18" r="1.5" fill="currentColor"/>
                </svg>
                Filtros
            </button>
            <span class="showing-text">
                Mostrando <?= min($offset + 1, $totalProductos) ?>–<?= min($offset + $porPagina, $totalProductos) ?> de <?= $totalProductos ?>
            </span>
        </div>

        <?php if (empty($productos)): ?>
            <div class="empty-state">
                <p>No se encontraron productos.</p>
                <a href="<?= SITE_URL ?>/" class="btn-primary">Ver todos</a>
            </div>
        <?php else: ?>

        <div class="products-grid">
            <?php foreach ($productos as $prod): ?>
            <article class="product-card">
                <a href="<?= SITE_URL ?>/producto.php?slug=<?= $prod['slug'] ?>" class="card-link">
                    <div class="card-image">
                        <?php if ($prod['imagen_principal']): ?>
                            <img src="<?= UPLOADS_URL . htmlspecialchars($prod['imagen_principal']) ?>"
                                 alt="<?= htmlspecialchars($prod['nombre']) ?>"
                                 loading="lazy">
                        <?php else: ?>
                            <div class="img-placeholder">
                                <span>AMS</span>
                            </div>
                        <?php endif; ?>

                        <?php if ($prod['nuevo']): ?>
                            <span class="badge badge-nuevo">Nuevo</span>
                        <?php endif; ?>
                        <?php if ($prod['precio_oferta']): ?>
                            <span class="badge badge-oferta">Oferta</span>
                        <?php endif; ?>

                        <div class="card-overlay">
                            <span>Ver producto</span>
                        </div>
                    </div>
                    <div class="card-info">
                        <span class="card-cat"><?= htmlspecialchars($prod['categoria_nombre'] ?? '') ?></span>
                        <h3 class="card-name"><?= htmlspecialchars($prod['nombre']) ?></h3>
                        <div class="card-price">
                            <?php if ($prod['precio_oferta']): ?>
                                <span class="price-old"><?= formatPrice($prod['precio']) ?></span>
                                <span class="price-new"><?= formatPrice($prod['precio_oferta']) ?></span>
                            <?php else: ?>
                                <span class="price-current"><?= formatPrice($prod['precio']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <button class="btn-add-cart" data-id="<?= $prod['id'] ?>" data-nombre="<?= htmlspecialchars($prod['nombre']) ?>">
                    Agregar al carrito
                </button>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Paginación -->
        <?php if ($totalPaginas > 1): ?>
        <nav class="pagination">
            <?php
            $queryBase = http_build_query(array_filter([
                'categoria' => $categoria,
                'q'         => $busqueda,
                'orden'     => $orden !== 'nombre' ? $orden : null,
            ]));
            ?>
            <?php if ($pagina > 1): ?>
                <a href="?<?= $queryBase ?>&p=<?= $pagina - 1 ?>" class="page-btn">← Anterior</a>
            <?php endif; ?>

            <?php for ($i = max(1, $pagina-2); $i <= min($totalPaginas, $pagina+2); $i++): ?>
                <a href="?<?= $queryBase ?>&p=<?= $i ?>"
                   class="page-btn <?= $i === $pagina ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($pagina < $totalPaginas): ?>
                <a href="?<?= $queryBase ?>&p=<?= $pagina + 1 ?>" class="page-btn">Siguiente →</a>
            <?php endif; ?>
        </nav>
        <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>