<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
check_auth();

$store = get_store_info($pdo);
$message = '';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $message = "Product deleted successfully.";
}

// toggle stock
if (isset($_GET['toggle_stock'])) {
    $id = (int)$_GET['toggle_stock'];
    $stmt = $pdo->prepare("UPDATE products SET stock_status = IF(stock_status='in_stock','out_of_stock','in_stock') WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: products.php");
    exit;
}

$sql = "
    SELECT p.*, c.name as category_name, i.image_path as main_image 
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN product_images i ON p.id = i.product_id AND i.is_primary = 1
    ORDER BY p.id DESC
";
$products = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | <?= htmlspecialchars($store['name']) ?></title>
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>:root { --primary-color: <?= htmlspecialchars($store['primary_color']) ?>; }</style>
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="admin-brand"><?= htmlspecialchars($store['name']) ?></div>
        <nav class="admin-nav">
            <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
            <a href="<?= BASE_URL ?>/admin/products.php" class="active">Products</a>
            <a href="<?= BASE_URL ?>/admin/orders.php">Orders</a>
            <a href="<?= BASE_URL ?>/admin/settings.php">Settings</a>
            <a href="<?= BASE_URL ?>/admin/logout.php" style="color:#ef4444; margin-top:auto;">Logout</a>
        </nav>
    </aside>
    <main class="admin-content">
        <div class="admin-header">
            <h1 style="font-size:2rem; margin:0;">Products</h1>
            <a href="<?= BASE_URL ?>/admin/add-product.php" class="btn btn-primary">+ Add New Product</a>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($products)): ?>
                        <tr><td colspan="6" style="text-align:center;">No products found.</td></tr>
                    <?php else: ?>
                        <?php foreach($products as $p): ?>
                            <tr>
                                <td>
                                    <img src="<?= BASE_URL ?>/uploads/products/<?= htmlspecialchars($p['main_image'] ?? 'placeholder.png') ?>" style="width:50px; height:50px; border-radius:8px; object-fit:cover;" alt="Img">
                                </td>
                                <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                                <td><?= htmlspecialchars($p['category_name']) ?></td>
                                <td><?= format_naira($p['price']) ?></td>
                                <td>
                                    <?php if($p['stock_status'] == 'in_stock'): ?>
                                        <a href="?toggle_stock=<?= $p['id'] ?>" class="status-badge status-completed" style="text-decoration:none;" title="Click to mark out of stock">In Stock</a>
                                    <?php else: ?>
                                        <a href="?toggle_stock=<?= $p['id'] ?>" class="status-badge status-cancelled" style="text-decoration:none;" title="Click to mark in stock">Out of Stock</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display:flex; gap:10px;">
                                        <a href="edit-product.php?id=<?= $p['id'] ?>" class="btn btn-outline" style="padding:6px 12px; font-size:0.85rem;">Edit</a>
                                        <a href="?delete=<?= $p['id'] ?>" class="btn" style="padding:6px 12px; font-size:0.85rem; background:#fee2e2; color:#991b1b;" onclick="return confirm('Delete this product?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
