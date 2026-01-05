<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $total_amount = $_POST['total_amount'];

    try {
        $pdo->beginTransaction();

        // 1. Insert Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_status) VALUES (:user_id, :total, 'completed')");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':total', $total_amount);
        $stmt->execute();
        $order_id = $pdo->lastInsertId();

        // 2. Insert Order Items
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $ids = implode(',', array_keys($_SESSION['cart']));
            $stmt_products = $pdo->query("SELECT * FROM products WHERE product_id IN ($ids)");
            $products = $stmt_products->fetchAll();

            $idx_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :qty, :price)");

            foreach ($products as $product) {
                $qty = $_SESSION['cart'][$product['product_id']];
                $price = $product['price'];
                
                $idx_stmt->bindParam(':order_id', $order_id);
                $idx_stmt->bindParam(':product_id', $product['product_id']);
                $idx_stmt->bindParam(':qty', $qty);
                $idx_stmt->bindParam(':price', $price);
                $idx_stmt->execute();

                // Optional: Update Stock
                // $pdo->query("UPDATE products SET stock = stock - $qty WHERE product_id = " . $product['product_id']);
            }
        }

        $pdo->commit();
        
        // Clear Cart
        unset($_SESSION['cart']);

        // Redirect to Confirmation
        header("Location: order_confirmation.php?order_id=$order_id");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
} else {
    header("Location: shop.php");
    exit;
}
?>
