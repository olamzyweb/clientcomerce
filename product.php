<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_GET['slug'])) {
    header('Location: ' . BASE_URL . '/shop.php');
    exit;
}

$slug = trim($_GET['slug']);

// Fetch product
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.slug = ?
");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    header('HTTP/1.0 404 Not Found');
    echo "Product not found";
    exit;
}

// Fetch images
$imgStmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC");
$imgStmt->execute([$product['id']]);
$images = $imgStmt->fetchAll();

$main_image = !empty($images) ? $images[0]['image_path'] : 'placeholder.png';

$page_title = $product['name'] . ' | ' . get_store_info($pdo)['name'];
$meta_desc = mb_strimwidth($product['description'], 0, 155, "...");
$og_image = '/uploads/products/' . $main_image;

require_once 'includes/header.php';
?>

<div class="container" style="padding:40px 0; margin-bottom:20px; font-size:0.9rem; color:#777;">
    <a href="<?= BASE_URL ?>/">Home</a> / <a href="<?= BASE_URL ?>/shop.php">Shop</a> / <?= htmlspecialchars($product['name']) ?>
</div>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="product-detail-flex">
            <div class="product-images">
                <div class="main-img">
                    <img src="<?= BASE_URL ?>/uploads/products/<?= htmlspecialchars($main_image) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <?php if(count($images) > 1): ?>
                <div class="thumb-grid">
                    <?php foreach($images as $index => $img): ?>
                        <img src="<?= BASE_URL ?>/uploads/products/<?= htmlspecialchars($img['image_path']) ?>" 
                             class="thumb-img <?= $index === 0 ? 'active' : '' ?>" 
                             alt="Thumbnail">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="product-data">
                <div class="product-category" style="margin-bottom:10px; font-weight:600;"><?= htmlspecialchars($product['category_name']) ?></div>
                <h1><?= htmlspecialchars($product['name']) ?></h1>
                
                <div class="price">
                    <?= format_naira($product['price']) ?>
                    <?php if($product['old_price'] > $product['price']): ?>
                        <span class="old"><?= format_naira($product['old_price']) ?></span>
                    <?php endif; ?>
                </div>

                <div style="margin-bottom:20px;">
                    <?php if($product['stock_status'] == 'in_stock'): ?>
                        <span style="background:#dcfce7; color:#166534; padding:5px 12px; border-radius:20px; font-weight:600; font-size:0.9rem;">&#10003; In Stock</span>
                    <?php else: ?>
                        <span style="background:#fee2e2; color:#991b1b; padding:5px 12px; border-radius:20px; font-weight:600; font-size:0.9rem;">Out of Stock</span>
                    <?php endif; ?>
                </div>

                <p class="desc"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                
                <?php if($product['stock_status'] == 'in_stock'): ?>
                    <div class="product-actions">
                        <div class="qty-selector">
                            <button class="qty-btn minus">-</button>
                            <input type="number" id="qty-input-<?= $product['id'] ?>" class="qty-input" value="1" min="1">
                            <button class="qty-btn plus">+</button>
                        </div>
                        <button class="btn btn-primary add-to-cart-btn" style="flex-grow:1; font-size:1.1rem;"
                                data-id="<?= $product['id'] ?>" 
                                data-name="<?= htmlspecialchars($product['name']) ?>"
                                data-price="<?= $product['price'] ?>"
                                data-image="<?= BASE_URL ?>/uploads/products/<?= htmlspecialchars($main_image) ?>">
                            Add to Cart
                        </button>
                    </div>
                <?php endif; ?>
                
                <div style="background:#f9f9f9; padding:20px; border-radius:12px; border:1px solid #eee; margin-top:20px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#25D366;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span style="font-weight:600;">Fast Order Support</span>
                    </div>
                    <p style="font-size:0.95rem; color:#666; margin:0;">Have questions? Message us directly on <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $store['whatsapp']) ?>" style="color:#25d366; font-weight:600;">WhatsApp</a>.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JSON-LD Structured Data Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?= htmlspecialchars($product['name']) ?>",
  "image": "<?= htmlspecialchars("http://".$_SERVER['HTTP_HOST']."/uploads/products/".$main_image) ?>",
  "description": "<?= trim(preg_replace('/\s+/', ' ', strip_tags($product['description']))) ?>",
  "offers": {
    "@type": "Offer",
    "url": "<?= htmlspecialchars("http://".$_SERVER['HTTP_HOST']."/product.php?slug=".$product['slug']) ?>",
    "priceCurrency": "NGN",
    "price": "<?= $product['price'] ?>",
    "availability": "<?= $product['stock_status'] == 'in_stock' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>"
  }
}
</script>

<?php require_once 'includes/footer.php'; ?>
