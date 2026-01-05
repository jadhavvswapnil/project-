<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Remove Action
if (isset($_GET['remove'])) {
    $w_id = $_GET['remove'];
    $del = $pdo->prepare("DELETE FROM wishlist WHERE wishlist_id = :id AND user_id = :uid");
    $del->execute([':id' => $w_id, ':uid' => $user_id]);
    echo "<script>window.location.href='wishlist.php';</script>";
    exit;
}

// Fetch Wishlist Items
$stmt = $pdo->prepare("SELECT w.wishlist_id, p.* FROM wishlist w JOIN products p ON w.product_id = p.product_id WHERE w.user_id = :uid");
$stmt->execute([':uid' => $user_id]);
$items = $stmt->fetchAll();
?>

<div class="container section-padding">
    <h2>My Wishlist</h2>
    
    <?php if(count($items) > 0): ?>
        <div class="products-grid" style="margin-top: 30px;">
            <?php foreach($items as $product): ?>
                <div class="product-card">
                    <div class="product-img">
                        <?php if($product['image'] && file_exists('uploads/'.$product['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                            <img src="https://placehold.co/400x400?text=<?php echo urlencode($product['name']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php endif; ?>
                        
                        <a href="wishlist.php?remove=<?php echo $product['wishlist_id']; ?>" class="add-to-wishlist" style="color: red;"><i class="fa-solid fa-trash"></i></a>
                        
                        <form action="add_to_cart_action.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                        </form>
                    </div>
                    <div class="product-info">
                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                        </a>
                        <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Your wishlist is empty. <a href="shop.php">Browse Products</a></p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
