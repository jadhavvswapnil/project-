<?php
require 'includes/db.php';

echo "<h2>Fixing All Product Images...</h2>";

// Complete mapping of all products to their local images
$imageUpdates = [
    // Plants
    ['Snake Plant', 'assets/images/snake plant.webp'],
    ['Monstera Deliciosa', 'assets/images/monstera deliciosa.jpg'],
    ['Peace Lily', 'assets/images/peace lily.webp'],
    ['Fiddle Leaf Fig', 'assets/images/fiddle leaf fig.webp'],
    ['Areca Palm', 'assets/images/areca palm.jpg'],
    ['ZZ Plant', 'assets/images/zz plant.avif'],
    ['Rubber Plant', 'assets/images/monstera deliciosa.jpg'], // Use monstera as fallback
    ['Pothos Golden', 'assets/images/peace lily.webp'], // Use peace lily as fallback
    ['Aloe Vera', 'assets/images/areca palm.jpg'], // Use areca palm as fallback
    
    // Pots
    ['Ceramic Pot', 'assets/images/ceramic pots.webp'],
    ['White Ceramic Pot', 'assets/images/ceramic pots.webp'],
    ['Terracotta Pot', 'assets/images/terracota plants pot.jpg'],
    ['Hanging Macrame', 'assets/images/ceramic pots.webp'],
    ['Geometric Metal', 'assets/images/ceramic pots.webp'],
    
    // Fertilizers
    ['Organic Fertilizer', 'assets/images/organic fertilizer.jpeg'],
    ['NPK Fertilizer', 'assets/images/npk fertilizer.webp'],
    ['Vermicompost', 'assets/images/vermi compost 5kg.jpg'],
    ['Seaweed Extract', 'assets/images/seaweed extract.jpg'],
    
    // Tools
    ['Pruning Shears', 'assets/images/pruning shears.webp'],
    ['Garden Gloves', 'assets/images/garden gloves.webp'],
    ['Watering Can', 'assets/images/watering can 2lit.jpg'],
    ['Soil Moisture Meter', 'assets/images/soil moisture meter.jpg'],
    ['Mini Garden Tool', 'assets/images/mini gardening tool set.webp'],
];

$updateStmt = $pdo->prepare("UPDATE products SET image = ? WHERE name LIKE ?");

$updated = 0;
foreach ($imageUpdates as $item) {
    $result = $updateStmt->execute([$item[1], '%' . $item[0] . '%']);
    $rows = $updateStmt->rowCount();
    if ($rows > 0) {
        $updated += $rows;
        echo "<p>✅ {$item[0]} → {$item[1]}</p>";
    }
}

echo "<h3>Updated: $updated products</h3>";

// Show all products with their images
echo "<h3>All Products:</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Name</th><th>Image Path</th><th>Status</th></tr>";

$products = $pdo->query("SELECT product_id, name, image FROM products ORDER BY product_id")->fetchAll();
foreach ($products as $p) {
    $img = $p['image'];
    $exists = file_exists($img) ? '✅ OK' : '❌ Missing';
    $color = file_exists($img) ? 'green' : 'red';
    echo "<tr><td>{$p['product_id']}</td><td>{$p['name']}</td><td>{$img}</td><td style='color:{$color}'>{$exists}</td></tr>";
}
echo "</table>";

echo "<p style='color:green;margin-top:20px;'><strong>Done!</strong></p>";
?>
