<?php include 'includes/header.php'; ?>

<div class="container section-padding" style="text-align: center; min-height: 50vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <div style="font-size: 5rem; color: var(--primary-color); margin-bottom: 20px;">
        <i class="fa-solid fa-circle-check"></i>
    </div>
    <h2 style="font-size: 2.5rem; margin-bottom: 15px;">Order Placed Successfully!</h2>
    <p style="font-size: 1.2rem; margin-bottom: 30px; color: #666;">Thank you for shopping with Greenly. Your order ID is #<?php echo htmlspecialchars($_GET['order_id']); ?>.</p>
    
    <div style="display: flex; gap: 20px;">
        <a href="shop.php" class="btn btn-outline">Continue Shopping</a>
        <a href="dashboard.php" class="btn btn-primary">View My Orders</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
