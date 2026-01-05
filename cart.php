<?php
include 'includes/db.php';
include 'includes/header.php';

$cart_items = [];
$total_price = 0;

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM products WHERE product_id IN ($ids)");
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['product_id']];
        $product['qty'] = $qty;
        $product['subtotal'] = $product['price'] * $qty;
        $cart_items[] = $product;
        $total_price += $product['subtotal'];
    }
}
?>

<div class="container section-padding">
    <h2>Your Cart</h2>
    
    <?php if(count($cart_items) > 0): ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="border-bottom: 2px solid #eee; text-align: left;">
                    <th style="padding: 10px;">Product</th>
                    <th style="padding: 10px;">Price</th>
                    <th style="padding: 10px;">Quantity</th>
                    <th style="padding: 10px;">Subtotal</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cart_items as $item): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px 10px; display: flex; align-items: center; gap: 15px;">
                            <?php if($item['image']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" width="50" style="border-radius: 5px;">
                            <?php else: ?>
                                <img src="https://placehold.co/50x50" width="50" style="border-radius: 5px;">
                            <?php endif; ?>
                            <?php echo htmlspecialchars($item['name']); ?>
                        </td>
                        <td style="padding: 10px;">₹<?php echo number_format($item['price'], 2); ?></td>
                        <td style="padding: 10px;"><?php echo $item['qty']; ?></td>
                        <td style="padding: 10px;">₹<?php echo number_format($item['subtotal'], 2); ?></td>
                        <td style="padding: 10px;">
                            <a href="add_to_cart_action.php?action=remove&id=<?php echo $item['product_id']; ?>" style="color: red;"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
            <div style="background-color: var(--card-bg); padding: 20px; border-radius: 10px; width: 300px; box-shadow: var(--shadow);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Subtotal:</span>
                    <strong>₹<?php echo number_format($total_price, 2); ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <span>Shipping:</span>
                    <span style="color: green;">Free</span>
                </div>
                <hr style="margin-bottom: 20px; border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 1.2rem;">
                    <span>Total:</span>
                    <strong>₹<?php echo number_format($total_price, 2); ?></strong>
                </div>
                
                <a href="checkout.php" class="btn btn-primary" style="width: 100%;">Proceed to Checkout</a>
            </div>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <p style="font-size: 1.2rem; margin-bottom: 20px;">Your cart is empty.</p>
            <a href="shop.php" class="btn btn-primary">Go to Shop</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
