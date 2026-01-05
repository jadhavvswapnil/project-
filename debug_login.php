<?php
require 'includes/db.php';

echo "<h2>Debug Login Test</h2>";

// Show database path
echo "<p><strong>DB File:</strong> " . realpath(__DIR__ . '/greenly.sqlite') . "</p>";

// Test query
$email = "test@greenly.com";
echo "<p><strong>Looking for:</strong> $email</p>";

$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":email", $email, PDO::PARAM_STR);
$stmt->execute();

echo "<p><strong>Rows found:</strong> " . $stmt->rowCount() . "</p>";

if ($row = $stmt->fetch()) {
    echo "<p><strong>User found:</strong> {$row['name']} ({$row['email']})</p>";
    echo "<p><strong>Password hash:</strong> " . substr($row['password'], 0, 20) . "...</p>";
    
    // Test password verification
    $test_password = "test123";
    if (password_verify($test_password, $row['password'])) {
        echo "<p style='color:green;'><strong>Password VALID!</strong></p>";
    } else {
        echo "<p style='color:red;'><strong>Password INVALID</strong></p>";
    }
} else {
    echo "<p style='color:red;'><strong>No user found!</strong></p>";
    
    // Show all users
    echo "<h3>All users in DB:</h3>";
    $all = $pdo->query("SELECT user_id, name, email FROM users")->fetchAll();
    foreach ($all as $u) {
        echo "<p>- {$u['name']} ({$u['email']})</p>";
    }
}
?>
