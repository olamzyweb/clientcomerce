<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_id'] = $user['id'];
        header('Location: ' . BASE_URL . '/admin/dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
$store = get_store_info($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?= htmlspecialchars($store['name']) ?></title>
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@400,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>:root { --primary-color: <?= htmlspecialchars($store['primary_color']) ?>; }</style>
</head>
<body>
    <div class="admin-login-wrapper">
        <div class="admin-card">
            <h2>Admin Login</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;">Sign In</button>
            </form>
            <div style="text-align:center; margin-top:20px; font-size:0.9rem;">
                <a href="<?= BASE_URL ?>/" style="color:#555;">&larr; Back to Store</a>
            </div>
        </div>
    </div>
</body>
</html>
