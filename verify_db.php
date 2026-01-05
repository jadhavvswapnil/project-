<?php
require 'includes/db.php';

echo "=== USERS ===\n";
$stmt = $pdo->query("SELECT user_id, name, email, role FROM users");
$users = $stmt->fetchAll();
print_r($users);

echo "\n=== PRODUCTS ===\n";
$stmt = $pdo->query("SELECT product_id, name, price FROM products");
$products = $stmt->fetchAll();
print_r($products);

echo "\n=== GARDENERS ===\n";
$stmt = $pdo->query("SELECT gardener_id, name, email FROM gardeners");
$gardeners = $stmt->fetchAll();
print_r($gardeners);

echo "\nDatabase verification complete!\n";
?>
