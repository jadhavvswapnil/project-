<?php
require 'includes/db.php';

echo "<h2>Adding Feedback Columns to Bookings Table...</h2>";

try {
    // Add rating column if not exists
    $pdo->exec("ALTER TABLE bookings ADD COLUMN rating INTEGER DEFAULT NULL");
    echo "<p>✅ Added 'rating' column</p>";
} catch (Exception $e) {
    echo "<p>⚠️ Rating column already exists or error: " . $e->getMessage() . "</p>";
}

try {
    // Add feedback column if not exists
    $pdo->exec("ALTER TABLE bookings ADD COLUMN feedback TEXT DEFAULT NULL");
    echo "<p>✅ Added 'feedback' column</p>";
} catch (Exception $e) {
    echo "<p>⚠️ Feedback column already exists or error: " . $e->getMessage() . "</p>";
}

// Update bookings table structure
echo "<h3>Bookings Table Structure:</h3>";
$pragma = $pdo->query("PRAGMA table_info(bookings)");
$columns = $pragma->fetchAll();
foreach ($columns as $col) {
    echo "<p>- {$col['name']} ({$col['type']})</p>";
}

echo "<p style='color:green;'><strong>Database schema updated!</strong></p>";
?>
