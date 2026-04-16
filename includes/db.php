<?php
$project_root = rtrim(str_replace(array('\\', '/'), '/', __DIR__), '/');
$project_root = dirname($project_root);
$doc_root = rtrim(str_replace(array('\\', '/'), '/', $_SERVER['DOCUMENT_ROOT']), '/');
$base_url = str_ireplace($doc_root, '', $project_root);
if (strpos($base_url, ':/') !== false || strpos($base_url, ':\\') !== false) {
    $base_url = '/clientcomerce';
}
define('BASE_URL', $base_url);

// c:\xampp\htdocs\clientcomerce\includes\db.php

$host = 'localhost';
$dbname = 'clientcomerce'; 
$username = 'root'; // DEFAULT XAMPP user
$password = ''; // DEFAULT XAMPP pass

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        die("<h2>Database not found.</h2><p>Please create the '$dbname' database via phpMyAdmin and import install/schema.sql and install/seed.sql</p>");
    }
    die("Database connection failed. Check your config.");
}
?>
