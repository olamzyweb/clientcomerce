<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
check_auth();

$store = get_store_info($pdo);

// fetch stats
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$pending_orders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total) FROM orders WHERE status = 'completed'")->fetchColumn();

// fetch recent orders
$recent_orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | <?= htmlspecialchars($store['name']) ?></title>
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
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="<?= BASE_URL ?>/admin/products.php" class="<?= in_array(basename($_SERVER['PHP_SELF']), ['products.php', 'add-product.php', 'edit-product.php']) ? 'active' : '' ?>">Products</a>
            <a href="<?= BASE_URL ?>/admin/orders.php" class="<?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>">Orders</a>
            <a href="<?= BASE_URL ?>/admin/settings.php" class="<?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : '' ?>">Settings</a>
            <a href="<?= BASE_URL ?>/admin/logout.php" style="color:#ef4444; margin-top:auto;">Logout</a>
        </nav>
    </aside>
    <main class="admin-content">
        <div class="admin-header">
            <h1 style="font-size:2rem; margin:0;">Dashboard Overview</h1>
            <a href="<?= BASE_URL ?>/" target="_blank" class="btn btn-outline">Live Preview</a>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:30px; margin-bottom:40px;">
            <div style="background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                <div style="color:#777; font-size:1.1rem; margin-bottom:10px;">Total Products</div>
                <div style="font-size:2.5rem; font-weight:700; color:var(--primary-color);"><?= number_format($total_products) ?></div>
            </div>
            <div style="background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                <div style="color:#777; font-size:1.1rem; margin-bottom:10px;">Pending Orders</div>
                <div style="font-size:2.5rem; font-weight:700; color:#d97706;"><?= number_format($pending_orders) ?></div>
            </div>
            <div style="background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                <div style="color:#777; font-size:1.1rem; margin-bottom:10px;">Completed Revenue</div>
                <div style="font-size:2.5rem; font-weight:700; color:#166534;"><?= format_naira((float)$total_revenue) ?></div>
            </div>
        </div>

        <h2 style="font-size:1.5rem; margin-bottom:20px;">Recent Orders</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($recent_orders)): ?>
                        <tr>
                            <td colspan="6" style="text-align:center; color:#777;">No recent orders.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($recent_orders as $order): ?>
                            <tr>
                                <td>#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td><?= htmlspecialchars($order['customer_phone']) ?></td>
                                <td><?= format_naira($order['total']) ?></td>
                                <td><span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                                <td><?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></td>
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
