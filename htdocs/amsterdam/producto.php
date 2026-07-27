<?php
require_once __DIR__ . '/config/db.php';

$db   = getDB();
$slug = $_GET['slug'] ?? '';

if (!$slug) {
    redirect(SITE_URL . '/');
}

// Producto
$stmt = $db->prepare("
    SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug
    FROM productos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    WHERE p.slug = ? AND p.estado = 'activo'
");
$stmt->execute([$slug]);
$producto = $stmt->fetch();

if (!$producto) {
    header("HTTP/1.0 404 Not Found");
    include __DIR__ . '/includes/header.php';
    echo '<div class="error-page"><h1>Producto no encontrado</h1><a href="' . SITE_URL . '/" class="btn-primary">Volver al catálogo</a></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Imágenes adicionales
$imgs = $db->prepare("SELECT * FROM producto_imagenes WHERE producto_id = ? ORDER BY orden");
$imgs->execute([$producto['id']]);
$imagenes = $imgs->fetchAll();

// Tallas
$tallas = $db->prepare("SELECT * FROM producto_tallas WHERE producto_id = ? ORDER BY FIELD(talla,'XS','S','M','L','XL','XXL') ");
$tallas->execute([$producto['id']]);
$tallas = $tallas->fetchAll();

// Productos relacionados
$rel = $db->prepare("
    SELECT * FROM productos
    WHERE categoria_id = ? AND id != ? AND estado = 'activo'
    ORDER BY RAND() LIMIT 4
");
$rel->execute([$producto['categoria_id'], $producto['id']]);
$relacionados = $rel->fetchAll();

$pageTitle = htmlspecialchars($producto['nombre']) . ' – AMS Swimwear';
include __DIR__ . '/includes/header.php';
?>

<section class="product-detail">
    <div class="breadcrumb">
        <a href="<?= SITE_URL ?>/">Inicio</a> /
        <?php if ($producto['categoria_slug']): ?>
            <a href="<?= SITE_URL ?>/?categoria=<?= $producto['categoria_slug'] ?>"><?= htmlspecialchars($producto['categoria_nombre']) ?></a> /
        <?php endif; ?>
        <span><?= htmlspecialchars($producto['nombre']) ?></span>
    </div>

    <div class="product-layout">
        <!-- Galería -->
        <div class="product-gallery">
            <div class="gallery-main">
                <img src="<?= $producto['imagen_principal'] ? UPLOADS_URL . $producto['imagen_principal'] : SITE_URL . '/assets/img/placeholder.jpg' ?>"
                     alt="<?= htmlspecialchars($producto['nombre']) ?>" id="mainImg">
            </div>
            <?php if (!empty($imagenes)): ?>
            <div class="gallery-thumbs">
                <img src="<?= UPLOADS_URL . $producto['imagen_principal'] ?>"
                     class="thumb active" onclick="setMainImg(this.src)">
                <?php foreach ($imagenes as $img): ?>
                    <img src="<?= UPLOADS_URL . $img['imagen'] ?>"
                         class="thumb" onclick="setMainImg(this.src)">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Info del producto -->
        <div class="product-info">
            <span class="product-cat"><?= htmlspecialchars($producto['categoria_nombre'] ?? '') ?></span>
            <h1 class="product-name"><?= htmlspecialchars($producto['nombre']) ?></h1>

            <div class="product-price">
                <?php if ($producto['precio_oferta']): ?>
                    <span class="price-old"><?= formatPrice($producto['precio']) ?></span>
                    <span class="price-new"><?= formatPrice($producto['precio_oferta']) ?></span>
                <?php else: ?>
                    <span class="price-current"><?= formatPrice($producto['precio']) ?></span>
                <?php endif; ?>
            </div>

            <?php if ($producto['descripcion']): ?>
            <div class="product-desc">
                <?= nl2br(htmlspecialchars($producto['descripcion'])) ?>
            </div>
            <?php endif; ?>

            <!-- Selección de talla -->
            <?php if (!empty($tallas)): ?>
            <div class="size-selector">
                <label>Talla <span id="selectedSize"></span></label>
                <div class="size-options">
                    <?php foreach ($tallas as $t): ?>
                    <button class="size-btn <?= $t['stock'] <= 0 ? 'out-of-stock' : '' ?>"
                            data-talla="<?= htmlspecialchars($t['talla']) ?>"
                            <?= $t['stock'] <= 0 ? 'disabled' : '' ?>>
                        <?= htmlspecialchars($t['talla']) ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Cantidad -->
            <div class="qty-selector">
                <label>Cantidad</label>
                <div class="qty-control">
                    <button type="button" id="qtyMinus">−</button>
                    <input type="number" id="qty" value="1" min="1" max="10">
                    <button type="button" id="qtyPlus">+</button>
                </div>
            </div>

            <button class="btn-primary btn-add-to-cart btn-block"
                    id="btnAddCart"
                    data-id="<?= $producto['id'] ?>"
                    data-nombre="<?= htmlspecialchars($producto['nombre']) ?>"
                    data-precio="<?= $producto['precio_oferta'] ?? $producto['precio'] ?>">
                Agregar al carrito
            </button>

            <div class="product-meta">
                <?php if ($producto['sku']): ?>
                    <span>SKU: <?= htmlspecialchars($producto['sku']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Productos relacionados -->
<?php if (!empty($relacionados)): ?>
<section class="related-products">
    <h2>También te puede gustar</h2>
    <div class="products-grid">
        <?php foreach ($relacionados as $rel): ?>
        <article class="product-card">
            <a href="<?= SITE_URL ?>/producto.php?slug=<?= $rel['slug'] ?>" class="card-link">
                <div class="card-image">
                    <?php if ($rel['imagen_principal']): ?>
                        <img src="<?= UPLOADS_URL . $rel['imagen_principal'] ?>" alt="<?= htmlspecialchars($rel['nombre']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="img-placeholder"><span>AMS</span></div>
                    <?php endif; ?>
                    <div class="card-overlay"><span>Ver producto</span></div>
                </div>
                <div class="card-info">
                    <h3 class="card-name"><?= htmlspecialchars($rel['nombre']) ?></h3>
                    <span class="price-current"><?= formatPrice($rel['precio']) ?></span>
                </div>
            </a>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<script>
function setMainImg(src) {
    document.getElementById('mainImg').src = src;
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    event.target.classList.add('active');
}

document.querySelectorAll('.size-btn:not([disabled])').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('selectedSize').textContent = '— ' + this.dataset.talla;
    });
});

document.getElementById('qtyMinus')?.addEventListener('click', () => {
    const q = document.getElementById('qty');
    if (q.value > 1) q.value--;
});
document.getElementById('qtyPlus')?.addEventListener('click', () => {
    const q = document.getElementById('qty');
    if (q.value < 10) q.value++;
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>