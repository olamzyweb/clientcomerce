<?php
// requires db.php to be included beforehand
require_once 'functions.php';
$store = get_store_info($pdo);
$settings = get_settings($pdo);

$page_title = $page_title ?? $settings['seo_title'] ?? $store['name'];
$meta_desc = $meta_desc ?? $settings['seo_description'] ?? '';
$og_image = $og_image ?? (isset($store['logo']) && !empty($store['logo']) ? '/uploads/' . $store['logo'] : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_desc) ?>">
    
    <!-- Open Graph tags -->
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_desc) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($og_image) ?>">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Google Fonts: Clash Display + Inter -->
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        :root {
            --primary-color: <?= htmlspecialchars($store['primary_color']) ?>;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container nav-container">
            <a href="<?= BASE_URL ?>/" class="brand">
                <?php if(!empty($store['logo'])): ?>
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($store['logo']) ?>" alt="<?= htmlspecialchars($store['name']) ?>">
                <?php else: ?>
                    <span class="brand-text"><?= htmlspecialchars($store['name']) ?></span>
                <?php endif; ?>
            </a>
            
            <button class="menu-toggle" aria-label="Toggle menu" id="mobile-menu-btn">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="nav-links" id="nav-menu">
                <a href="<?= BASE_URL ?>/">Home</a>
                <a href="<?= BASE_URL ?>/shop.php">Shop</a>
                <a href="<?= BASE_URL ?>/about.php">About Us</a>
                <a href="<?= BASE_URL ?>/contact.php">Contact</a>
            </nav>

            <div class="nav-actions">
                <a href="<?= BASE_URL ?>/cart.php" class="cart-btn" aria-label="Cart">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span id="cart-count">0</span>
                </a>
            </div>
        </div>
    </header>
    <main>
