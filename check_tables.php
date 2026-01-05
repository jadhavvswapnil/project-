<?php
require 'includes/db.php';

echo "<h2>Database Table Structure Check</h2>";

// Check orders table
echo "<h3>Orders Table:</h3>";
try {
    $pragma = $pdo->query("PRAGMA table_info(orders)");
    $cols = $pragma->fetchAll();
    if (empty($cols)) {
        echo "<p style='color:red;'>Orders table not found!</p>";
    } else {
        foreach ($cols as $col) {
            echo "<p>- {$col['name']} ({$col['type']})</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

// Check order_items table
echo "<h3>Order Items Table:</h3>";
try {
    $pragma = $pdo->query("PRAGMA table_info(order_items)");
    $cols = $pragma->fetchAll();
    if (empty($cols)) {
        echo "<p style='color:red;'>Order items table not found!</p>";
    } else {
        foreach ($cols as $col) {
            echo "<p>- {$col['name']} ({$col['type']})</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

// Check services table (for bookings)
echo "<h3>Services Table:</h3>";
try {
    $pragma = $pdo->query("PRAGMA table_info(services)");
    $cols = $pragma->fetchAll();
    if (empty($cols)) {
        echo "<p style='color:red;'>Services table not found!</p>";
    } else {
        foreach ($cols as $col) {
            echo "<p>- {$col['name']} ({$col['type']})</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

// Check bookings table
echo "<h3>Bookings Table:</h3>";
try {
    $pragma = $pdo->query("PRAGMA table_info(bookings)");
    $cols = $pragma->fetchAll();
    if (empty($cols)) {
        echo "<p style='color:red;'>Bookings table not found!</p>";
    } else {
        foreach ($cols as $col) {
            echo "<p>- {$col['name']} ({$col['type']})</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<p style='color:green;'><strong>Check complete!</strong></p>";
?>
