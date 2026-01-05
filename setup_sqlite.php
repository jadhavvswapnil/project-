<?php
$db_file = __DIR__ . '/greenly.sqlite';

try {
    $pdo = new PDO("sqlite:$db_file");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        user_id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT DEFAULT 'customer',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Gardeners
    $pdo->exec("CREATE TABLE IF NOT EXISTS gardeners (
        gardener_id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        experience TEXT,
        rating REAL DEFAULT 0.0,
        status TEXT DEFAULT 'available',
        image TEXT
    )");

    // Categories
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        category_id INTEGER PRIMARY KEY AUTOINCREMENT,
        category_name TEXT UNIQUE NOT NULL
    )");

    // Products
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        product_id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        category_id INTEGER,
        price REAL NOT NULL,
        description TEXT,
        image TEXT,
        stock INTEGER DEFAULT 0,
        FOREIGN KEY (category_id) REFERENCES categories(category_id)
    )");

    // Cart (Simplified for SQLite - usually session based but we kept DB table in design)
    $pdo->exec("CREATE TABLE IF NOT EXISTS cart (
        cart_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        product_id INTEGER,
        quantity INTEGER DEFAULT 1,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
    )");

    // Wishlist
    $pdo->exec("CREATE TABLE IF NOT EXISTS wishlist (
        wishlist_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        product_id INTEGER,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
    )");

    // Orders
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        order_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        total_amount REAL NOT NULL,
        payment_status TEXT DEFAULT 'pending',
        order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    )");
    
    // Order Items
    $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
        order_item_id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER,
        product_id INTEGER,
        quantity INTEGER,
        price REAL,
        FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(product_id)
    )");

    // Services
    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        service_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        gardener_id INTEGER,
        service_type TEXT NOT NULL,
        service_status TEXT DEFAULT 'pending',
        service_date DATE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (gardener_id) REFERENCES gardeners(gardener_id)
    )");

    // Feedback
    $pdo->exec("CREATE TABLE IF NOT EXISTS feedback (
        feedback_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        rating INTEGER,
        comment TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    )");

    // Insert Dummy Data
    // Check if empty first
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO categories (category_name) VALUES ('Plants'), ('Pots'), ('Fertilizers'), ('Gardening Tools')");
        
        $pdo->exec("INSERT INTO gardeners (name, email, password, experience, rating, status) VALUES 
        ('Ramesh Kumar', 'ramesh@greenly.com', '\$2y\$10\$dummyhash', '5 Years', 4.5, 'available'),
        ('Suresh Singh', 'suresh@greenly.com', '\$2y\$10\$dummyhash', '3 Years', 4.0, 'available')");

        $pdo->exec("INSERT INTO products (name, category_id, price, description, stock) VALUES
        ('Snake Plant', 1, 350.00, 'Air purifying plant, low maintenance.', 50),
        ('Ceramic Pot', 2, 500.00, 'Premium ceramic pot 10 inch.', 20),
        ('Organic Fertilizer', 3, 150.00, 'Chemical free fertilizer for all plants.', 100),
        ('Pruning Shears', 4, 250.00, 'Stainless steel gardening scissors.', 30)");

        echo "Database created and dummy data inserted.\n";
    } else {
        echo "Database already exists.\n";
    }

} catch(PDOException $e) {
    die("DB Setup failed: " . $e->getMessage());
}
?>
