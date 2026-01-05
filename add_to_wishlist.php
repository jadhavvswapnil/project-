<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_GET['product_id'])) {
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : $_GET['product_id'];
    $user_id = $_SESSION['user_id'];

    // Check if exists
    $check = $pdo->prepare("SELECT * FROM wishlist WHERE user_id = :uid AND product_id = :pid");
    $check->execute([':uid' => $user_id, ':pid' => $product_id]);

    if ($check->rowCount() == 0) {
        $ins = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (:uid, :pid)");
        $ins->execute([':uid' => $user_id, ':pid' => $product_id]);
    }
}

// Redirect back
$referer = $_SERVER['HTTP_REFERER'] ?? 'shop.php';
header("Location: $referer");
exit;
?>
