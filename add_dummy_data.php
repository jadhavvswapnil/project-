<?php
require 'includes/db.php';

echo "<h2>Adding More Products with Images...</h2>";

// More products with Unsplash image URLs
$products = [
    // Plants
    ['Monstera Deliciosa', 1, 899.00, 'The iconic Swiss Cheese Plant with large, beautiful split leaves. Perfect for indoor spaces.', 'https://images.unsplash.com/photo-1614594975525-e45c5a5e1e40?w=400', 25],
    ['Peace Lily', 1, 450.00, 'Elegant flowering plant that purifies air. Low light tolerant.', 'https://images.unsplash.com/photo-1593691509543-c55fb32e1a8b?w=400', 40],
    ['Fiddle Leaf Fig', 1, 1200.00, 'Statement plant with large violin-shaped leaves. Instagram favorite!', 'https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?w=400', 15],
    ['Rubber Plant', 1, 550.00, 'Hardy indoor plant with glossy dark green leaves.', 'https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?w=400', 35],
    ['Pothos Golden', 1, 250.00, 'Easy-care trailing vine with heart-shaped leaves. Perfect for beginners.', 'https://images.unsplash.com/photo-1572688484438-313a6e50c333?w=400', 60],
    ['Areca Palm', 1, 750.00, 'Tropical palm that brings a vacation vibe to any room.', 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400', 20],
    ['ZZ Plant', 1, 400.00, 'Nearly indestructible plant perfect for low light and forgetful waterers.', 'https://images.unsplash.com/photo-1632207691143-d5f21a06629c?w=400', 45],
    ['Aloe Vera', 1, 199.00, 'Medicinal succulent with healing gel. Great for sunny spots.', 'https://images.unsplash.com/photo-1509423350716-97f9360b4e09?w=400', 80],
    
    // Pots
    ['White Ceramic Pot 8"', 2, 599.00, 'Minimalist white ceramic pot with drainage hole. Modern design.', 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=400', 30],
    ['Terracotta Pot Set', 2, 450.00, 'Set of 3 classic terracotta pots in different sizes.', 'https://images.unsplash.com/photo-1416879855530-63131c4027da?w=400', 25],
    ['Hanging Macrame Planter', 2, 350.00, 'Bohemian style macrame hanger for trailing plants.', 'https://images.unsplash.com/photo-1520412099551-62b6bafeb5bb?w=400', 40],
    ['Geometric Metal Stand', 2, 899.00, 'Modern gold metal plant stand. Holds pots up to 10".', 'https://images.unsplash.com/photo-1534349762230-e0cadf78f5da?w=400', 15],
    
    // Fertilizers
    ['NPK Fertilizer 1kg', 3, 199.00, 'Balanced nutrition for all plants. Use monthly.', 'https://images.unsplash.com/photo-1628174411623-289e6eb1cf37?w=400', 100],
    ['Vermicompost 5kg', 3, 350.00, '100% organic worm compost. Rich in nutrients.', 'https://images.unsplash.com/photo-1628174411623-289e6eb1cf37?w=400', 50],
    ['Seaweed Extract', 3, 280.00, 'Liquid organic fertilizer for lush green growth.', 'https://images.unsplash.com/photo-1628174411623-289e6eb1cf37?w=400', 60],
    
    // Tools
    ['Garden Gloves', 4, 199.00, 'Breathable cotton gloves with grip. One size fits all.', 'https://tse3.mm.bing.net/th/id/OIP.OD6JaHog20WLKo2EM00aaAHaHa?cb=ucfimg2&ucfimg=1&rs=1&pid=ImgDetMain&o=7&rm=3', 100],
    ['Watering Can 2L', 4, 450.00, 'Elegant copper-finish watering can with long spout.', 'https://image.architonic.com/img_pro2-4/119/5447/evergreen-watering-can-2l-red-b.jpg', 30],
    ['Soil Moisture Meter', 4, 299.00, 'Digital meter to check when your plants need water.', 'https://m.media-amazon.com/images/I/715FR1x3VGL._AC_SL1500_.jpg', 40],
    ['Mini Garden Tool Set', 4, 550.00, 'Set of 5 mini tools - spade, rake, fork, trowel, weeder.', 'https://i5.walmartimages.com/seo/Arghm-Mini-Garden-Tool-Set-Clearance-3-Pieces-Gardening-Kit-7-09x1-97inch-Shovel-and-Rake-for-Indoor-Plants_050e3fec-ad10-4d90-b9fd-6c9014d100e6.896914d11112cc6861798fa05d2fea7f.jpeg', 25],
];

// Update existing products with images
$existingUpdates = [
    ['Snake Plant', 'https://images.unsplash.com/photo-1593691509543-c55fb32e1a8b?w=400'],
    ['Ceramic Pot', 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=400'],
    ['Organic Fertilizer', 'https://images.unsplash.com/photo-1628174411623-289e6eb1cf37?w=400'],
    ['Pruning Shears', 'https://i5.walmartimages.com/seo/Augper-Garden-Pruning-Shears-Stainless-Steel-Blades-Handheld-Pruners-Premium-Bypass-Pruning-Shears-for-Your-Garden_85020269-af99-4b7a-ac83-b2a758ab52ae.1291512fcfed910a5c22353b6d78a6f8.jpeg'],
];

foreach ($existingUpdates as $update) {
    $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE name = ?");
    $stmt->execute([$update[1], $update[0]]);
}
echo "<p>Updated existing products with images.</p>";

// Insert new products
$insert = $pdo->prepare("INSERT OR IGNORE INTO products (name, category_id, price, description, image, stock) VALUES (?, ?, ?, ?, ?, ?)");

$added = 0;
foreach ($products as $p) {
    try {
        $insert->execute($p);
        if ($pdo->lastInsertId()) $added++;
    } catch (Exception $e) {
        // Skip if already exists
    }
}

echo "<p>Added $added new products.</p>";

// Add more gardeners
$gardeners = [
    ['Amit Sharma', 'amit@greenly.com', password_hash('gardener123', PASSWORD_DEFAULT), '7 Years', 4.8, 'available'],
    ['Priya Patel', 'priya@greenly.com', password_hash('gardener123', PASSWORD_DEFAULT), '4 Years', 4.6, 'available'],
    ['Ravi Kumar', 'ravi@greenly.com', password_hash('gardener123', PASSWORD_DEFAULT), '6 Years', 4.7, 'available'],
];

$gInsert = $pdo->prepare("INSERT OR IGNORE INTO gardeners (name, email, password, experience, rating, status) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($gardeners as $g) {
    try {
        $gInsert->execute($g);
    } catch (Exception $e) {}
}
echo "<p>Added gardeners.</p>";

// Show summary
echo "<h3>Product Summary:</h3>";
$cats = $pdo->query("SELECT c.category_name, COUNT(p.product_id) as count FROM categories c LEFT JOIN products p ON c.category_id = p.category_id GROUP BY c.category_id")->fetchAll();
foreach ($cats as $c) {
    echo "<p>- {$c['category_name']}: {$c['count']} products</p>";
}

echo "<p style='color:green;'><strong>Done! Database updated successfully.</strong></p>";
?>
