<?php
require 'includes/db.php';

// Create test user with proper password hash
$name = "Test User";
$email = "test@greenly.com";
$password = password_hash("test123", PASSWORD_DEFAULT);
$role = "customer";

// Check if exists first
$check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$check->execute([$email]);

if ($check->rowCount() > 0) {
    // Update password if user exists
    $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->execute([$password, $email]);
    echo "User updated with new password hash.\n";
} else {
    // Insert new user
    $insert = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $insert->execute([$name, $email, $password, $role]);
    echo "User created successfully.\n";
}

echo "\n=== TEST CREDENTIALS ===\n";
echo "Email: test@greenly.com\n";
echo "Password: test123\n";
echo "Role: Customer\n";

// Show all users
echo "\n=== ALL USERS ===\n";
$stmt = $pdo->query("SELECT user_id, name, email, role FROM users");
foreach ($stmt->fetchAll() as $user) {
    echo "- {$user['name']} ({$user['email']}) - {$user['role']}\n";
}
?>
