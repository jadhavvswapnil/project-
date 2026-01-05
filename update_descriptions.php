<?php
require 'includes/db.php';

echo "<h2>Updating Product Descriptions...</h2>";

// Detailed descriptions for all products
$updates = [
    // Plants
    ['Snake Plant', 'The Snake Plant (Sansevieria) is one of the most low-maintenance houseplants. It thrives in low light, requires minimal watering, and is excellent at purifying indoor air by removing toxins like formaldehyde and benzene. Perfect for bedrooms and offices.'],
    ['Monstera Deliciosa', 'The iconic Swiss Cheese Plant features large, glossy leaves with natural holes. Native to tropical rainforests, it brings a dramatic jungle vibe to any space. Prefers bright indirect light and regular misting. A must-have for plant enthusiasts!'],
    ['Peace Lily', 'The Peace Lily is famous for its elegant white flowers and air-purifying abilities. It thrives in low to medium light and signals when thirsty by slightly drooping. NASA rates it among the top plants for removing indoor pollutants.'],
    ['Fiddle Leaf Fig', 'The Fiddle Leaf Fig is the ultimate statement plant with its large, violin-shaped leaves. It prefers bright, indirect light and consistent watering. A favorite of interior designers for adding a touch of sophistication to any room.'],
    ['Rubber Plant', 'The Rubber Plant features thick, glossy dark green leaves that add elegance to any space. Its hardy and tolerant of neglect, making it perfect for beginners. Can grow up to 8 feet tall indoors with proper care.'],
    ['Pothos Golden', 'Golden Pothos is the perfect beginner plant with its trailing vines and heart-shaped leaves. It thrives in various light conditions and can even grow in water. Excellent for hanging baskets or letting it trail from shelves.'],
    ['Areca Palm', 'The Areca Palm brings tropical vibes with its feathery, arching fronds. It acts as a natural humidifier and air purifier. Place in bright indirect light and keep soil consistently moist for best results.'],
    ['ZZ Plant', 'The ZZ Plant (Zamioculcas) is virtually indestructible with its glossy, dark green leaves. Perfect for low-light areas and forgetful waterers. It can go weeks without water and still thrive beautifully.'],
    ['Aloe Vera', 'Aloe Vera is both beautiful and functional. Its gel has healing properties for burns and skin care. Loves bright light and infrequent watering. Keep on a sunny windowsill for best growth.'],
    
    // Pots
    ['White Ceramic Pot 8"', 'Elegant minimalist white ceramic pot with a modern matte finish. Features a drainage hole with matching saucer to prevent root rot. Perfect for medium-sized plants like Pothos or Peace Lily.'],
    ['Ceramic Pot', 'Premium quality ceramic pot with a sleek design. The 10-inch size is ideal for medium to large plants. Includes drainage hole and a complementary saucer. Available in classic white.'],
    ['Terracotta Pot Set', 'Classic terracotta pot set including 3 sizes (4", 6", 8"). Terracotta is porous, allowing roots to breathe and preventing overwatering. Perfect for succulents, herbs, and small houseplants.'],
    ['Hanging Macrame Planter', 'Bohemian-style handwoven macrame plant hanger made from natural cotton rope. Adjustable length up to 40 inches. Fits pots up to 8 inches. Adds a cozy, artisanal touch to any room.'],
    ['Geometric Metal Stand', 'Modern geometric metal plant stand with gold finish. Holds pots up to 10 inches. Height: 15 inches. Elevates your plants for better visibility and adds a contemporary touch to decor.'],
    
    // Fertilizers
    ['Organic Fertilizer', 'Premium chemical-free organic fertilizer made from natural ingredients. Provides balanced NPK nutrition for all plants. Safe for indoor use. Apply once a month for healthy, vibrant growth.'],
    ['NPK Fertilizer 1kg', 'Balanced NPK 19-19-19 fertilizer for comprehensive plant nutrition. Promotes strong root development, lush foliage, and beautiful blooms. Water-soluble formula for easy application. Suitable for all plants.'],
    ['Vermicompost 5kg', '100% organic vermicompost (worm castings) rich in beneficial microbes and nutrients. Improves soil structure and water retention. Perfect for potting mix or as top dressing. Chemical-free and eco-friendly.'],
    ['Seaweed Extract', 'Concentrated liquid seaweed fertilizer with natural growth hormones. Boosts plant immunity, root growth, and stress tolerance. Dilute 2ml per liter of water. Safe for all plants including edibles.'],
    
    // Tools
    ['Pruning Shears', 'Professional-grade stainless steel pruning shears with ergonomic grip. Sharp bypass blades for clean cuts. Spring-loaded for easy operation. Perfect for trimming, shaping, and deadheading plants.'],
    ['Garden Gloves', 'Durable gardening gloves with reinforced fingertips and breathable cotton fabric. Provides protection while maintaining dexterity. One size fits most. Machine washable and long-lasting.'],
    ['Watering Can 2L', 'Elegant 2-liter watering can with copper-finish coating. Long spout for precise watering. Removable rose attachment for gentle shower. Perfect size for indoor plant care.'],
    ['Soil Moisture Meter', 'Digital soil moisture meter for accurate readings. No batteries required! Simply insert probe into soil to check moisture level. Helps prevent both overwatering and underwatering.'],
    ['Mini Garden Tool Set', 'Complete 5-piece mini garden tool set including trowel, transplanter, rake, fork, and weeder. Ergonomic handles with comfortable grip. Compact size perfect for indoor gardening and repotting.'],
];

$updateStmt = $pdo->prepare("UPDATE products SET description = ? WHERE name LIKE ?");

$updated = 0;
foreach ($updates as $update) {
    $result = $updateStmt->execute([$update[1], '%' . $update[0] . '%']);
    if ($updateStmt->rowCount() > 0) {
        $updated++;
        echo "<p>✅ Updated: {$update[0]}</p>";
    }
}

echo "<h3>Total Updated: $updated products</h3>";
echo "<p style='color:green;'><strong>All descriptions updated successfully!</strong></p>";
?>
