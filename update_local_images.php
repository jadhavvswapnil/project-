<?php
require 'includes/db.php';

echo "<h2>Updating Products with Local Images...</h2>";

// Map product names to local image paths
$imageUpdates = [
    // Plants
    ['Snake Plant', 'assets/images/snake plant.webp'],
    ['Monstera Deliciosa', 'assets/images/monstera deliciosa.jpg'],
    ['Peace Lily', 'assets/images/peace lily.webp'],
    ['Fiddle Leaf Fig', 'assets/images/fiddle leaf fig.webp'],
    ['Areca Palm', 'assets/images/areca palm.jpg'],
    
    // Pots
    ['Ceramic Pot', 'assets/images/ceramic pots.webp'],
    ['White Ceramic Pot', 'assets/images/ceramic pots.webp'],
    ['Terracotta Pot Set', 'assets/images/terracota plants pot.jpg'],
    
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
    ['Mini Garden Tool Set', 'assets/images/mini gardening tool set.webp'],
];

$updateStmt = $pdo->prepare("UPDATE products SET image = ? WHERE name LIKE ?");

$updated = 0;
foreach ($imageUpdates as $item) {
    $result = $updateStmt->execute([$item[1], '%' . $item[0] . '%']);
    if ($updateStmt->rowCount() > 0) {
        $updated++;
        echo "<p>✅ Updated: {$item[0]} → {$item[1]}</p>";
    } else {
        echo "<p>⚠️ Not found: {$item[0]}</p>";
    }
}

echo "<h3>Total Updated: $updated products</h3>";
echo "<p style='color:green;'><strong>Local images linked successfully!</strong></p>";

// Show all products with images
echo "<h3>All Products with Images:</h3>";
$products = $pdo->query("SELECT name, image FROM products")->fetchAll();
foreach ($products as $p) {
    $status = (strpos($p['image'], 'assets/') === 0) ? '📁 Local' : '🌐 External';
    echo "<p>{$status} | {$p['name']} → {$p['image']}</p>";
}
?>
