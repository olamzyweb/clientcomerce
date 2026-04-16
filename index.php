<?php
require_once 'includes/db.php';

$page_title = 'Home';

// Fetch featured products
$stmt = $pdo->query("
    SELECT p.*, c.name as category_name, i.image_path as main_image 
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN product_images i ON p.id = i.product_id AND i.is_primary = 1
    WHERE p.featured = 1 AND p.stock_status = 'in_stock'
    LIMIT 4
");
$featured_products = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Premium Quality,<br>Unbeatable Prices.</h1>
            <p>Discover the finest <?= htmlspecialchars($store['name']) ?> has to offer. Everything you need, curated just for you.</p>
            <a href="<?= BASE_URL ?>/shop.php" class="btn btn-primary hero-btn">Shop Now</a>
            <a href="<?= BASE_URL ?>/about.php" class="btn btn-outline hero-btn" style="border-color:#fff; color:#fff; margin-left:10px;">Our Story</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Collection</h2>
            <a href="<?= BASE_URL ?>/shop.php" style="font-weight:600; color:var(--primary-color);">View All &rarr;</a>
        </div>
        
        <div class="product-grid">
            <?php foreach($featured_products as $product): ?>
                <div class="product-card">
                    <a href="<?= BASE_URL ?>/product.php?slug=<?= htmlspecialchars($product['slug']) ?>" class="product-img-wrap">
                        <?php if($product['old_price'] > $product['price']): ?>
                            <span class="badge sale">Sale!</span>
                        <?php endif; ?>
                        <img src="<?= BASE_URL ?>/uploads/products/<?= htmlspecialchars($product['main_image'] ?? 'placeholder.png') ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
                    </a>
                    <div class="product-info">
                        <div class="product-category"><?= htmlspecialchars($product['category_name']) ?></div>
                        <a href="<?= BASE_URL ?>/product.php?slug=<?= htmlspecialchars($product['slug']) ?>" class="product-title"><?= htmlspecialchars($product['name']) ?></a>
                        <div class="product-price">
                            <?= format_naira($product['price']) ?>
                            <?php if($product['old_price'] > $product['price']): ?>
                                <span class="old-price"><?= format_naira($product['old_price']) ?></span>
                            <?php endif; ?>
                        </div>
                        <button class="btn add-to-cart-btn" 
                                data-id="<?= $product['id'] ?>" 
                                data-name="<?= htmlspecialchars($product['name']) ?>"
                                data-price="<?= $product['price'] ?>"
                                data-image="<?= BASE_URL ?>/uploads/products/<?= htmlspecialchars($product['main_image'] ?? 'placeholder.png') ?>">
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="section-header" style="justify-content:center;">
            <h2>Why Shop With Us?</h2>
        </div>
        <div class="trust-indicators">
            <div class="trust-item">
                <div class="trust-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div class="trust-title">Premium Quality</div>
                <div class="trust-desc">We source only the best authentic items for you.</div>
            </div>
            <div class="trust-item">
                <div class="trust-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>
                <div class="trust-title">Nationwide Delivery</div>
                <div class="trust-desc">Fast logistics to anywhere in Nigeria.</div>
            </div>
            <div class="trust-item">
                <div class="trust-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <div class="trust-title">Secure Payments</div>
                <div class="trust-desc">Pay via Bank Transfer or Pay on Delivery.</div>
            </div>
            <div class="trust-item">
                <div class="trust-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <div class="trust-title">24/7 Support</div>
                <div class="trust-desc">We are always available via WhatsApp.</div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
