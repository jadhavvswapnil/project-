<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<script>window.location.href='shop.php';</script>";
    exit;
}

// Calculate Total
$total_price = 0;
$ids = implode(',', array_keys($_SESSION['cart']));
$stmt = $pdo->query("SELECT * FROM products WHERE product_id IN ($ids)");
$products = $stmt->fetchAll();

foreach ($products as $product) {
    $qty = $_SESSION['cart'][$product['product_id']];
    $total_price += $product['price'] * $qty;
}
?>

<div class="container section-padding">
    <h2>Checkout</h2>
    <div class="grid-sidebar" style="grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 20px;">
        
        <!-- Order Summary -->
        <div class="auth-box" style="width: 100%; text-align: left;">
            <h3 style="margin-bottom: 20px;">Order Summary</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <?php foreach($products as $product): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px 0;"><?php echo htmlspecialchars($product['name']); ?> x <?php echo $_SESSION['cart'][$product['product_id']]; ?></td>
                        <td style="text-align: right;">₹<?php echo number_format($product['price'] * $_SESSION['cart'][$product['product_id']], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td style="padding: 20px 0; font-weight: bold;">Total</td>
                    <td style="padding: 20px 0; text-align: right; font-weight: bold; color: var(--primary-color);">₹<?php echo number_format($total_price, 2); ?></td>
                </tr>
            </table>
        </div>

        <!-- Payment Details (Mock) -->
        <div class="auth-box" style="width: 100%; text-align: left;">
            <h3 style="margin-bottom: 20px;">Payment Details</h3>
            <p style="margin-bottom: 20px; color: #666;">This is a simulated payment gateway.</p>
            
            <form action="place_order_action.php" method="post" id="checkout-form">
                <div class="form-group">
                    <label>Card Number (Mock)</label>
                    <input type="text" placeholder="XXXX-XXXX-XXXX-XXXX" value="4242-4242-4242-4242" readonly style="background: #f9f9f9;">
                </div>
                <div class="form-group" style="display: flex; gap: 20px;">
                    <div>
                        <label>Expiry</label>
                        <input type="text" value="12/25" readonly style="background: #f9f9f9;">
                    </div>
                    <div>
                        <label>CVV</label>
                        <input type="text" value="123" readonly style="background: #f9f9f9;">
                    </div>
                </div>

                <input type="hidden" name="total_amount" value="<?php echo $total_price; ?>">
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Pay ₹<?php echo number_format($total_price, 2); ?></button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
