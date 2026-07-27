<?php
// ============================================
// AMS SWIMWEAR - Configuración de Base de Datos
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');         // Cambia por tu usuario
define('DB_PASS', 'programacion');             // Cambia por tu contraseña
define('DB_NAME', 'ams_swimwear');
define('DB_CHARSET', 'utf8mb4');

define('SITE_URL', 'http://localhost/ams-swimwear');  // Cambia en producción
define('SITE_NAME', 'AMS Swimwear');
define('UPLOADS_DIR', __DIR__ . '/../uploads/products/');
define('UPLOADS_URL', SITE_URL . '/uploads/products/');

// ---- Conexión PDO ----
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

// ---- Helpers generales ----
function slugify(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $text = str_replace(['á','é','í','ó','ú','ü','ñ'], ['a','e','i','o','u','u','n'], $text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', trim($text));
    return $text;
}

function formatPrice(float $price): string {
    return '$' . number_format($price, 2, '.', ',');
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['admin_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect(SITE_URL . '/admin/login.php');
    }
}

session_start();