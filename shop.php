<?php
require_once 'includes/db.php';

$page_title = 'Shop All Products';

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$where = ["p.stock_status = 'in_stock'"];
$params = [];

if ($category_id) {
    $where[] = "p.category_id = :cat_id";
    $params[':cat_id'] = $category_id;
}
if ($search) {
    $where[] = "p.name LIKE :search";
    $params[':search'] = "%$search%";
}

$where_clause = implode(' AND ', $where);

// Fetch products
$sql = "
    SELECT p.*, c.name as category_name, i.image_path as main_image 
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN product_images i ON p.id = i.product_id AND i.is_primary = 1
    WHERE $where_clause
    ORDER BY p.id DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch categories for filter
$cats = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-banner">
    <div class="container">
        <h1>Shop Collection</h1>
        <p>Find the best items tailored for your lifestyle.</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="filter-bar">
            <form method="GET" action="shop.php" style="display:flex; gap:15px; flex-wrap:wrap; width:100%;">
                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach($cats as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="search-input" placeholder="Search products...">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <?php if(empty($products)): ?>
            <div style="text-align:center; padding:50px 0;">
                <h3 style="color:#777;">No products found matching your criteria.</h3>
                <a href="<?= BASE_URL ?>/shop.php" class="btn btn-outline" style="margin-top:20px;">Clear Filters</a>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach($products as $product): ?>
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
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
