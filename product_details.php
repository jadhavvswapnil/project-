<?php
include 'includes/db.php';
include 'includes/header.php';

$product_id = isset($_GET['id']) ? $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.category_id WHERE p.product_id = :id");
$stmt->bindParam(':id', $product_id);
$stmt->execute();
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='container section-padding'><h2>Product not found</h2></div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="container section-padding">
    <div class="grid-sidebar" style="grid-template-columns: 1fr 1fr; align-items: start;">
        <div class="product-detail-img">
             <?php 
             $img = $product['image'];
             // Handle different image path types: assets/, uploads/, http URLs
             if($img && (strpos($img, 'http') === 0 || strpos($img, 'assets/') === 0 || file_exists($img) || file_exists('uploads/'.$img))): 
                 if(strpos($img, 'http') === 0 || strpos($img, 'assets/') === 0 || file_exists($img)) {
                     $imgSrc = $img;
                 } else {
                     $imgSrc = 'uploads/'.$img;
                 }
             ?>
                <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; border-radius: 20px; box-shadow: var(--shadow);">
            <?php else: ?>
                <img src="https://placehold.co/600x600/e8f5e9/2e7d32?text=<?php echo urlencode($product['name']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; border-radius: 20px; box-shadow: var(--shadow);">
            <?php endif; ?>
        </div>
        
        <div class="product-detail-info">
            <span class="highlight"><?php echo htmlspecialchars($product['category_name']); ?></span>
            <h1 style="font-size: 2.5rem; margin-bottom: 10px;"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="price" style="font-size: 2rem; color: var(--primary-color); font-weight: bold;">₹<?php echo number_format($product['price'], 2); ?></p>
            
            <p style="margin: 20px 0; font-size: 1.1rem; color: #666;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            
            <div style="margin-bottom: 30px;">
                <strong>Availability:</strong> 
                <?php if($product['stock'] > 0): ?>
                    <span style="color: green;">In Stock (<?php echo $product['stock']; ?> items)</span>
                <?php else: ?>
                    <span style="color: red;">Out of Stock</span>
                <?php endif; ?>
            </div>

            <form action="add_to_cart_action.php" method="post" style="display: flex; gap: 10px;">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 80px; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                <button type="submit" name="add_to_cart" class="btn btn-primary" <?php echo ($product['stock'] <= 0) ? 'disabled' : ''; ?>>Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
