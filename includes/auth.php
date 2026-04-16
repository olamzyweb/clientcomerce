<?php
session_start();

function check_auth() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}
?>
