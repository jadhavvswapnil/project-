<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle delete order
if (isset($_GET['delete'])) {
    $order_id = intval($_GET['delete']);
    
    // Delete order items first
    $del_items = $pdo->prepare("DELETE FROM order_items WHERE order_id = :oid");
    $del_items->execute([':oid' => $order_id]);
    
    // Delete the order
    $del_order = $pdo->prepare("DELETE FROM orders WHERE order_id = :oid AND user_id = :uid");
    $del_order->execute([':oid' => $order_id, ':uid' => $user_id]);
    
    $message = '<div class="alert success"><i class="fa-solid fa-check"></i> Order deleted successfully!</div>';
}

// Handle cancel order
if (isset($_GET['cancel'])) {
    $order_id = intval($_GET['cancel']);
    $update = $pdo->prepare("UPDATE orders SET payment_status = 'cancelled' WHERE order_id = :oid AND user_id = :uid");
    $update->execute([':oid' => $order_id, ':uid' => $user_id]);
    $message = '<div class="alert warning"><i class="fa-solid fa-ban"></i> Order cancelled!</div>';
}

// Fetch orders - using correct column names
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :uid ORDER BY order_date DESC");
$stmt->execute([':uid' => $user_id]);
$orders = $stmt->fetchAll();
?>

<div class="container section-padding">
    <div class="page-header">
        <h2><i class="fa-solid fa-box"></i> My Orders</h2>
        <a href="shop.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Shop More</a>
    </div>
    
    <?php echo $message; ?>
    
    <?php if(count($orders) > 0): ?>
        <div class="orders-list">
            <?php foreach($orders as $order): 
                // Get order items
                $items_stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = :oid");
                $items_stmt->execute([':oid' => $order['order_id']]);
                $items = $items_stmt->fetchAll();
                
                $status = $order['payment_status'];
                $status_class = '';
                switch($status) {
                    case 'completed': $status_class = 'status-success'; break;
                    case 'cancelled': $status_class = 'status-danger'; break;
                    case 'processing': $status_class = 'status-warning'; break;
                    default: $status_class = 'status-info';
                }
            ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <span class="order-id">#<?php echo $order['order_id']; ?></span>
                            <span class="order-date"><?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?></span>
                        </div>
                        <span class="order-status <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span>
                    </div>
                    
                    <?php if(count($items) > 0): ?>
                    <div class="order-items">
                        <?php foreach($items as $item): ?>
                            <div class="order-item">
                                <?php 
                                $img = $item['image'];
                                if($img && strpos($img, 'assets/') === 0) {
                                    $imgSrc = $img;
                                } else {
                                    $imgSrc = 'https://placehold.co/50x50/e8f5e9/2e7d32?text=' . urlencode(substr($item['name'], 0, 2));
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div class="item-details">
                                    <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                    <span>Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 0); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="order-footer">
                        <div class="order-total">
                            <strong>Total: ₹<?php echo number_format($order['total_amount'], 0); ?></strong>
                        </div>
                        <div class="order-actions">
                            <a href="invoice.php?type=order&id=<?php echo $order['order_id']; ?>" class="btn btn-invoice btn-sm" target="_blank">
                                <i class="fa-solid fa-file-invoice"></i> Invoice
                            </a>
                            <?php if($status == 'pending' || $status == 'processing'): ?>
                                <a href="my_orders.php?cancel=<?php echo $order['order_id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Cancel this order?')">
                                    <i class="fa-solid fa-ban"></i> Cancel
                                </a>
                            <?php endif; ?>
                            <a href="my_orders.php?delete=<?php echo $order['order_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this order from history?')">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-box-open"></i>
            <h3>No orders yet</h3>
            <p>Start shopping to see your orders here!</p>
            <a href="shop.php" class="btn btn-primary">Browse Products</a>
        </div>
    <?php endif; ?>
</div>

<style>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.orders-list { display: flex; flex-direction: column; gap: 20px; }
.order-card { background: var(--card-bg); border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
.order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
.order-id { font-weight: 700; font-size: 1.1rem; margin-right: 15px; }
.order-date { color: #666; font-size: 0.9rem; }
.order-status { padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
.status-success { background: #e8f5e9; color: #2e7d32; }
.status-danger { background: #ffebee; color: #c62828; }
.status-warning { background: #fff3e0; color: #e65100; }
.status-info { background: #e3f2fd; color: #1565c0; }
.order-items { display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 15px; }
.order-item { display: flex; align-items: center; gap: 10px; background: var(--bg-color); padding: 10px; border-radius: 10px; }
.order-item img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
.item-details span { display: block; font-size: 0.85rem; color: #666; }
.order-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid var(--border-color); }
.order-actions { display: flex; gap: 10px; }
.btn-sm { padding: 8px 15px; font-size: 0.85rem; }
.btn-warning { background: #ff9800; color: white; }
.btn-danger { background: #f44336; color: white; }
.btn-invoice { background: #2196f3; color: white; }
.alert { padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
.alert.success { background: #e8f5e9; color: #2e7d32; }
.alert.warning { background: #fff3e0; color: #e65100; }
.empty-state { text-align: center; padding: 60px 20px; background: var(--card-bg); border-radius: 20px; }
.empty-state i { font-size: 4rem; color: var(--primary-color); margin-bottom: 20px; display: block; }
</style>

<?php include 'includes/footer.php'; ?>
