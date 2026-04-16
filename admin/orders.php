<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
check_auth();

$store = get_store_info($pdo);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
    $message = "Order #{$_POST['order_id']} status updated to {$_POST['status']}.";
}

$orders = $pdo->query("SELECT * FROM orders ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | <?= htmlspecialchars($store['name']) ?></title>
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
            <a href="<?= BASE_URL ?>/admin/orders.php" class="active">Orders</a>
            <a href="<?= BASE_URL ?>/admin/settings.php">Settings</a>
            <a href="<?= BASE_URL ?>/admin/logout.php" style="color:#ef4444; margin-top:auto;">Logout</a>
        </nav>
    </aside>
    <main class="admin-content">
        <div class="admin-header">
            <h1 style="font-size:2rem; margin:0;">Order Management</h1>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Details</th>
                        <th>Payload Summary</th>
                        <th>Total</th>
                        <th>Status / Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($orders)): ?>
                        <tr><td colspan="5" style="text-align:center;">No orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach($orders as $order): ?>
                            <?php 
                            $payload = json_decode($order['items_json'], true); 
                            $items = $payload['items'] ?? [];
                            $address = $payload['address'] ?? 'N/A';
                            $payment = $payload['payment'] ?? 'N/A';
                            ?>
                            <tr>
                                <td>#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?><br><span style="font-size:0.8rem; color:#888;"><?= date('M j, Y', strtotime($order['created_at'])) ?></span></td>
                                <td>
                                    <strong><?= htmlspecialchars($order['customer_name']) ?></strong><br>
                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $order['customer_phone']) ?>" target="_blank" style="color:#25d366; font-size:0.9rem; font-weight:600; text-decoration:underline;">
                                        <?= htmlspecialchars($order['customer_phone']) ?>
                                    </a><br>
                                    <span style="font-size:0.85rem; color:#555;"><?= htmlspecialchars($address) ?></span>
                                </td>
                                <td style="width:300px; white-space:normal;">
                                    <ul style="padding-left:15px; font-size:0.9rem; margin-bottom:5px;">
                                        <?php foreach($items as $i): ?>
                                            <li><?= $i['qty'] ?>x <?= htmlspecialchars($i['name']) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div style="font-size:0.85rem; color:#666;">Payment: <?= htmlspecialchars($payment) ?></div>
                                </td>
                                <td><strong style="color:var(--primary-color);"><?= format_naira($order['total']) ?></strong></td>
                                <td>
                                    <form method="POST" style="display:flex; gap:10px; align-items:center;">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="status" class="form-control" style="padding:8px; width:auto; border-radius:6px; font-size:0.9rem;">
                                            <option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>Pending</option>
                                            <option value="seen" <?= $order['status']=='seen'?'selected':'' ?>>Seen / Processing</option>
                                            <option value="completed" <?= $order['status']=='completed'?'selected':'' ?>>Completed</option>
                                            <option value="cancelled" <?= $order['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary" style="padding:8px 15px; font-size:0.9rem;">Save</button>
                                    </form>
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
