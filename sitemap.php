<?php
require_once 'includes/db.php';
header("Content-Type: application/xml; charset=utf-8");

$base_url = "http://" . $_SERVER['HTTP_HOST'];

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// Static pages
$pages = ['/', '/shop.php', '/about.php', '/contact.php'];
foreach ($pages as $page) {
    echo '<url>' . PHP_EOL;
    echo '<loc>' . htmlspecialchars($base_url . $page) . '</loc>' . PHP_EOL;
    echo '<changefreq>weekly</changefreq>' . PHP_EOL;
    echo '<priority>' . ($page == '/' ? '1.0' : '0.8') . '</priority>' . PHP_EOL;
    echo '</url>' . PHP_EOL;
}

// Active Products
try {
    $products = $pdo->query("SELECT slug, created_at FROM products WHERE stock_status = 'in_stock'")->fetchAll();
    foreach ($products as $p) {
        echo '<url>' . PHP_EOL;
        echo '<loc>' . htmlspecialchars($base_url . '/product.php?slug=' . $p['slug']) . '</loc>' . PHP_EOL;
        echo '<lastmod>' . date('Y-m-d', strtotime($p['created_at'])) . '</lastmod>' . PHP_EOL;
        echo '<changefreq>monthly</changefreq>' . PHP_EOL;
        echo '<priority>0.9</priority>' . PHP_EOL;
        echo '</url>' . PHP_EOL;
    }
} catch (Exception $e) {
    // Ignore DB errors in xml output
}

echo '</urlset>';
?>
