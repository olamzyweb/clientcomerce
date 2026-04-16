<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
check_auth();

$store = get_store_info($pdo);
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$error = '';
$success = '';

if (!isset($_GET['id'])) { header('Location: products.php'); exit; }
$product_id = (int)$_GET['id'];

// fetch product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();
if(!$product) { header('Location: products.php'); exit; }

// get primary image
$imgStmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? AND is_primary = 1");
$imgStmt->execute([$product_id]);
$primary_img = $imgStmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $old_price = !empty($_POST['old_price']) ? $_POST['old_price'] : null;
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    try {
        $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, old_price=?, category_id=?, featured=? WHERE id=?");
        $stmt->execute([$name, $description, $price, $old_price, $category_id, $featured, $product_id]);

        // Handle Image
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/products/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $target_path = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                // Delete old image record
                $pdo->prepare("DELETE FROM product_images WHERE product_id = ?")->execute([$product_id]);
                // Insert new image
                $pdo->prepare("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 1)")
                    ->execute([$product_id, $filename]);
            }
        }
        $success = "Product updated successfully!";
        // refresh product variable
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        $imgStmt->execute([$product_id]);
        $primary_img = $imgStmt->fetchColumn();

    } catch(Exception $e) {
        $error = "Error updating product: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | <?= htmlspecialchars($store['name']) ?></title>
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>:root { --primary-color: <?= htmlspecialchars($store['primary_color']) ?>; }</style>
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar" id="admin-sidebar">
        <!-- Reusable sidebar (abbreviated) -->
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
            <h1 style="font-size:2rem; margin:0;">Edit Product</h1>
            <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline">&larr; Back to Products</a>
        </div>

        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div style="background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.03); max-width:800px;">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label>Price (₦)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Old Price (For 'Sale' badge) - Optional</label>
                        <input type="number" step="0.01" name="old_price" class="form-control" value="<?= htmlspecialchars($product['old_price']) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category...</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <div class="form-group" style="padding:15px; background:#fafafa; border-radius:8px;">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="checkbox" name="featured" value="1" <?= $product['featured'] ? 'checked' : '' ?> style="width:20px; height:20px;">
                        <span>Feature on Homepage</span>
                    </label>
                </div>

                <div class="form-group">
                    <label>Update Primary Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <p style="font-size:0.85rem; color:#777; margin-top:5px;">Leave blank to keep current image.</p>
                    <?php if($primary_img): ?>
                        <div style="margin-top:10px;">
                            <img src="<?= BASE_URL ?>/uploads/products/<?= htmlspecialchars($primary_img) ?>" style="height:100px; border-radius:8px;">
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary" style="padding:15px 40px; font-size:1.1rem; margin-top:10px;">Save Changes</button>
            </form>
        </div>
    </main>
</div>
</body>
</html>
