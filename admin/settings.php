<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
check_auth();

$store = get_store_info($pdo);
$settings = get_settings($pdo);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update store info
    $stmt = $pdo->prepare("UPDATE store_info SET 
        name=?, phone=?, whatsapp=?, email=?, address=?, delivery_areas=?,
        bank_name=?, account_number=?, account_name=?, primary_color=?
        WHERE id = ?");
    
    $stmt->execute([
        $_POST['name'], $_POST['phone'], $_POST['whatsapp'], $_POST['email'],
        $_POST['address'], $_POST['delivery_areas'], $_POST['bank_name'],
        $_POST['account_number'], $_POST['account_name'], $_POST['primary_color'],
        $store['id'] ?? 1
    ]);

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        
        $filename = time() . '_' . basename($_FILES['logo']['name']);
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
            $pdo->prepare("UPDATE store_info SET logo=? WHERE id=?")->execute([$filename, $store['id'] ?? 1]);
        }
    } elseif (isset($_POST['remove_logo'])) {
        $pdo->prepare("UPDATE store_info SET logo=NULL WHERE id=?")->execute([$store['id'] ?? 1]);
    }

    // Update settings
    $settings_to_update = ['seo_title', 'seo_description', 'currency_symbol'];
    foreach ($settings_to_update as $key) {
        if (isset($_POST[$key])) {
            $pdo->prepare("INSERT INTO settings (key_name, key_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE key_value = ?")
                ->execute([$key, $_POST[$key], $_POST[$key]]);
        }
    }

    $message = "Settings updated successfully!";
    $store = get_store_info($pdo); // refresh
    $settings = get_settings($pdo); // refresh
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Settings | <?= htmlspecialchars($store['name']) ?></title>
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
            <a href="<?= BASE_URL ?>/admin/products.php">Products</a>
            <a href="<?= BASE_URL ?>/admin/orders.php">Orders</a>
            <a href="<?= BASE_URL ?>/admin/settings.php" class="active">Settings</a>
            <a href="<?= BASE_URL ?>/admin/logout.php" style="color:#ef4444; margin-top:auto;">Logout</a>
        </nav>
    </aside>
    <main class="admin-content">
        <div class="admin-header">
            <h1 style="font-size:2rem; margin:0;">Store Settings</h1>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" style="max-width:800px;">
            <div style="background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.03); margin-bottom:30px;">
                <h2 style="font-size:1.3rem; margin-bottom:20px;">Basic Information</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label>Store Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($store['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Primary Brand Color</label>
                        <input type="color" name="primary_color" class="form-control" value="<?= htmlspecialchars($store['primary_color'] ?? '#832232') ?>" style="padding:5px; height:50px; cursor:pointer;" required>
                    </div>
                    <div class="form-group">
                        <label>Store Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <?php if(!empty($store['logo'])): ?>
                            <div style="margin-top:10px; display:flex; align-items:center; gap:15px;">
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($store['logo']) ?>" style="height:40px;">
                                <label style="display:flex; align-items:center; gap:5px; cursor:pointer; color:#ef4444; font-size:0.9rem;">
                                    <input type="checkbox" name="remove_logo" value="1">
                                    Remove Logo
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Currency Symbol</label>
                        <input type="text" name="currency_symbol" class="form-control" value="<?= htmlspecialchars($settings['currency_symbol'] ?? '₦') ?>">
                    </div>
                </div>
            </div>

            <div style="background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.03); margin-bottom:30px;">
                <h2 style="font-size:1.3rem; margin-bottom:20px;">Contact & Location</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($store['phone']) ?>">
                    </div>
                    <div class="form-group">
                        <label>WhatsApp Number (Intl Format without +)</label>
                        <input type="text" name="whatsapp" class="form-control" value="<?= htmlspecialchars($store['whatsapp']) ?>" placeholder="2348000000000">
                    </div>
                    <div class="form-group" style="grid-column:1 / -1;">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($store['email']) ?>">
                    </div>
                    <div class="form-group" style="grid-column:1 / -1;">
                        <label>Physical Address</label>
                        <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($store['address']) ?></textarea>
                    </div>
                    <div class="form-group" style="grid-column:1 / -1;">
                        <label>Delivery Areas (Comma separated)</label>
                        <textarea name="delivery_areas" class="form-control" rows="2"><?= htmlspecialchars($store['delivery_areas']) ?></textarea>
                    </div>
                </div>
            </div>

            <div style="background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.03); margin-bottom:30px;">
                <h2 style="font-size:1.3rem; margin-bottom:20px;">Bank Details (For Transfers)</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($store['bank_name']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Account / Sort Code / Type</label>
                        <input type="text" name="account_number" class="form-control" value="<?= htmlspecialchars($store['account_number']) ?>">
                    </div>
                    <div class="form-group" style="grid-column:1 / -1;">
                        <label>Account Name</label>
                        <input type="text" name="account_name" class="form-control" value="<?= htmlspecialchars($store['account_name']) ?>">
                    </div>
                </div>
            </div>

            <div style="background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.03); margin-bottom:30px;">
                <h2 style="font-size:1.3rem; margin-bottom:20px;">SEO Details</h2>
                <div class="form-group">
                    <label>Global SEO Title</label>
                    <input type="text" name="seo_title" class="form-control" value="<?= htmlspecialchars($settings['seo_title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Global SEO Description</label>
                    <textarea name="seo_description" class="form-control" rows="2"><?= htmlspecialchars($settings['seo_description'] ?? '') ?></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="padding:15px 40px; font-size:1.1rem;">Save All Settings</button>
        </form>
    </main>
</div>
</body>
</html>
