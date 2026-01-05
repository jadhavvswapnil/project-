-- Database: greenly_db

CREATE DATABASE IF NOT EXISTS greenly_db;
USE greenly_db;

-- Users Table (Customers and Admins)
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Gardeners Table
CREATE TABLE IF NOT EXISTS gardeners (
    gardener_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    experience VARCHAR(100),
    rating DECIMAL(3, 2) DEFAULT 0.0,
    status ENUM('available', 'busy', 'offline') DEFAULT 'available',
    image VARCHAR(255)
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) UNIQUE NOT NULL
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category_id INT,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

-- Cart Table
CREATE TABLE IF NOT EXISTS cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Wishlist Table
CREATE TABLE IF NOT EXISTS wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Order Items Table (To store products in an order - Good practice, though not explicitly requested, it's needed for functionality)
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    gardener_id INT DEFAULT NULL,
    service_type VARCHAR(100) NOT NULL,
    service_status ENUM('pending', 'accepted', 'completed', 'cancelled') DEFAULT 'pending',
    service_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (gardener_id) REFERENCES gardeners(gardener_id) ON DELETE SET NULL
);

-- Feedback Table
CREATE TABLE IF NOT EXISTS feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Insert Dummy Data (Categories)
INSERT INTO categories (category_name) VALUES ('Plants'), ('Pots'), ('Fertilizers'), ('Gardening Tools');

-- Insert Dummy Data (Gardeners)
INSERT INTO gardeners (name, email, password, experience, rating, status) VALUES 
('Ramesh Kumar', 'ramesh@greenly.com', '$2y$10$dummyhash', '5 Years', 4.5, 'available'),
('Suresh Singh', 'suresh@greenly.com', '$2y$10$dummyhash', '3 Years', 4.0, 'available');

-- Insert Dummy Data (Products)
INSERT INTO products (name, category_id, price, description, stock) VALUES
('Snake Plant', 1, 350.00, 'Air purifying plant, low maintenance.', 50),
('Ceramic Pot', 2, 500.00, 'Premium ceramic pot 10 inch.', 20),
('Organic Fertilizer', 3, 150.00, 'Chemical free fertilizer for all plants.', 100),
('Pruning Shears', 4, 250.00, 'Stainless steel gardening scissors.', 30);
