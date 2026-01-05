<?php
include 'includes/db.php';
include 'includes/header.php';

$category_slug = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build Query
$sql = "SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.category_id WHERE 1=1";
$params = [];

if ($category_slug) {
    $sql .= " AND LOWER(c.category_name) = :category";
    $params[':category'] = strtolower($category_slug);
}

if ($search) {
    $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
    $params[':search'] = "%$search%";
}

// Sorting
switch($sort) {
    case 'price_low':
        $sql .= " ORDER BY p.price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY p.price DESC";
        break;
    case 'name':
        $sql .= " ORDER BY p.name ASC";
        break;
    default:
        $sql .= " ORDER BY p.product_id DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get Categories with counts
$cat_stmt = $pdo->query("SELECT c.*, COUNT(p.product_id) as product_count 
                         FROM categories c 
                         LEFT JOIN products p ON c.category_id = p.category_id 
                         GROUP BY c.category_id");
$categories = $cat_stmt->fetchAll();

$total_count = count($products);
?>

<!-- Shop Header Banner -->
<div class="shop-banner">
    <div class="container">
        <h1><?php echo $category_slug ? ucfirst($category_slug) : 'Shop All Products'; ?></h1>
        <p>Find the perfect addition to your garden from our curated collection</p>
    </div>
</div>

<div class="container section-padding">
    <div class="shop-layout">
        <!-- Sidebar Filters -->
        <aside class="shop-sidebar">
            <!-- Search Widget -->
            <div class="sidebar-widget">
                <h3><i class="fa-solid fa-search"></i> Search</h3>
                <form action="shop.php" method="get" class="search-form">
                    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit"><i class="fa-solid fa-arrow-right"></i></button>
                </form>
            </div>

            <!-- Categories Widget -->
            <div class="sidebar-widget">
                <h3><i class="fa-solid fa-layer-group"></i> Categories</h3>
                <ul class="category-list">
                    <li>
                        <a href="shop.php" class="<?php echo $category_slug == '' ? 'active' : ''; ?>">
                            <span>All Products</span>
                            <span class="count"><?php echo $total_count; ?></span>
                        </a>
                    </li>
                    <?php foreach($categories as $cat): ?>
                        <li>
                            <a href="shop.php?category=<?php echo strtolower($cat['category_name']); ?>" 
                               class="<?php echo $category_slug == strtolower($cat['category_name']) ? 'active' : ''; ?>">
                                <span><?php echo htmlspecialchars($cat['category_name']); ?></span>
                                <span class="count"><?php echo $cat['product_count']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Price Range Widget -->
            <div class="sidebar-widget">
                <h3><i class="fa-solid fa-indian-rupee-sign"></i> Price Range</h3>
                <div class="price-links">
                    <a href="shop.php?sort=price_low" class="<?php echo $sort == 'price_low' ? 'active' : ''; ?>">Low to High</a>
                    <a href="shop.php?sort=price_high" class="<?php echo $sort == 'price_high' ? 'active' : ''; ?>">High to Low</a>
                </div>
            </div>

            <!-- Help Widget -->
            <div class="sidebar-widget help-widget">
                <div class="help-icon"><i class="fa-solid fa-headset"></i></div>
                <h4>Need Help?</h4>
                <p>Call us at<br><strong>+91 98765 43210</strong></p>
            </div>
        </aside>

        <!-- Main Product Area -->
        <main class="shop-main">
            <!-- Shop Toolbar -->
            <div class="shop-toolbar">
                <div class="results-info">
                    <span>Showing <strong><?php echo $total_count; ?></strong> products</span>
                    <?php if($search): ?>
                        <span class="search-term">for "<em><?php echo htmlspecialchars($search); ?></em>"</span>
                    <?php endif; ?>
                </div>
                <div class="sort-options">
                    <label>Sort by:</label>
                    <select onchange="window.location.href=this.value">
                        <option value="shop.php?sort=newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="shop.php?sort=name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                        <option value="shop.php?sort=price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="shop.php?sort=price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>
            </div>

            <?php if($total_count > 0): ?>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                        <div class="product-card">
                            <div class="product-img">
                                <?php if($product['stock'] < 10): ?>
                                    <span class="product-badge">Low Stock</span>
                                <?php endif; ?>
                                
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
                                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <img src="https://placehold.co/400x400/e8f5e9/2e7d32?text=<?php echo urlencode($product['name']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php endif; ?>
                                
                                <div class="product-actions">
                                    <a href="add_to_wishlist.php?product_id=<?php echo $product['product_id']; ?>" class="action-btn wishlist-btn" title="Add to Wishlist">
                                        <i class="fa-regular fa-heart"></i>
                                    </a>
                                    <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="action-btn view-btn" title="Quick View">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                </div>
                                
                                <form action="add_to_cart_action.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                            
                            <div class="product-info">
                                <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                                <a href="product_details.php?id=<?php echo $product['product_id']; ?>">
                                    <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                </a>
                                <p class="product-desc"><?php echo htmlspecialchars(substr($product['description'], 0, 80)); ?>...</p>
                                <div class="product-bottom">
                                    <p class="price">₹<?php echo number_format($product['price'], 0); ?></p>
                                    <span class="stock-info <?php echo $product['stock'] > 10 ? 'in-stock' : 'low-stock'; ?>">
                                        <?php echo $product['stock'] > 10 ? 'In Stock' : 'Only ' . $product['stock'] . ' left'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <i class="fa-solid fa-seedling"></i>
                    <h3>No products found</h3>
                    <p>Try adjusting your search or filter to find what you're looking for.</p>
                    <a href="shop.php" class="btn btn-primary">View All Products</a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<style>
/* Shop Page Enhanced Styles */
.shop-banner {
    background: linear-gradient(135deg, var(--primary-color), #1b5e20);
    color: white;
    padding: 60px 0;
    margin-top: 70px;
    text-align: center;
}

.shop-banner h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.shop-banner p {
    opacity: 0.9;
    font-size: 1.1rem;
}

.shop-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 40px;
    align-items: start;
}

/* Sidebar Styles */
.shop-sidebar {
    position: sticky;
    top: 90px;
}

.sidebar-widget {
    background: var(--card-bg);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.sidebar-widget h3 {
    font-size: 1.1rem;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar-widget h3 i {
    color: var(--primary-color);
}

.search-form {
    display: flex;
    border: 2px solid var(--border-color);
    border-radius: 25px;
    overflow: hidden;
}

.search-form input {
    flex: 1;
    padding: 12px 15px;
    border: none;
    background: transparent;
    font-size: 0.95rem;
}

.search-form button {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 18px;
    cursor: pointer;
}

.category-list {
    list-style: none;
}

.category-list li a {
    display: flex;
    justify-content: space-between;
    padding: 12px 15px;
    margin-bottom: 8px;
    border-radius: 8px;
    background: var(--bg-color);
    transition: all 0.3s;
}

.category-list li a:hover,
.category-list li a.active {
    background: var(--primary-color);
    color: white;
}

.category-list .count {
    background: rgba(255,255,255,0.2);
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 0.85rem;
}

.price-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.price-links a {
    padding: 10px 15px;
    background: var(--bg-color);
    border-radius: 8px;
    text-align: center;
    transition: all 0.3s;
}

.price-links a:hover,
.price-links a.active {
    background: var(--primary-color);
    color: white;
}

.help-widget {
    background: linear-gradient(135deg, var(--primary-color), #43a047);
    color: white;
    text-align: center;
}

.help-widget .help-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

/* Shop Toolbar */
.shop-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background: var(--card-bg);
    border-radius: 15px;
    margin-bottom: 30px;
}

.sort-options {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sort-options select {
    padding: 10px 15px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-color);
    cursor: pointer;
}

/* Product Card Enhanced */
.product-category {
    display: inline-block;
    font-size: 0.75rem;
    color: var(--primary-color);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.product-desc {
    font-size: 0.9rem;
    color: #666;
    margin: 10px 0 15px;
    line-height: 1.5;
}

.product-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

.stock-info {
    font-size: 0.8rem;
    padding: 4px 10px;
    border-radius: 15px;
}

.stock-info.in-stock {
    background: #e8f5e9;
    color: #2e7d32;
}

.stock-info.low-stock {
    background: #fff3e0;
    color: #e65100;
}

.product-actions {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.95);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.action-btn:hover {
    background: var(--primary-color);
    color: white;
}

/* No Products */
.no-products {
    text-align: center;
    padding: 80px 40px;
    background: var(--card-bg);
    border-radius: 20px;
}

.no-products i {
    font-size: 4rem;
    color: var(--primary-color);
    margin-bottom: 20px;
}

/* Responsive */
@media (max-width: 992px) {
    .shop-layout {
        grid-template-columns: 1fr;
    }
    
    .shop-sidebar {
        position: static;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media (max-width: 576px) {
    .shop-sidebar {
        grid-template-columns: 1fr;
    }
    
    .shop-toolbar {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
