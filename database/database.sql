-- DUMBLEDORE BOUTIQUE - Database Schema
-- MySQL Database Setup for Luxury Ecommerce Website

-- Create Database
CREATE DATABASE IF NOT EXISTS dumbledore_boutique;
USE dumbledore_boutique;

-- =====================================================
-- Users Table
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    postal_code VARCHAR(10),
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Admin Users Table
-- =====================================================
CREATE TABLE IF NOT EXISTS admin (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    admin_name VARCHAR(100) NOT NULL,
    admin_email VARCHAR(100) UNIQUE NOT NULL,
    admin_password VARCHAR(255) NOT NULL,
    admin_phone VARCHAR(15),
    role ENUM('superadmin', 'admin', 'manager') DEFAULT 'admin',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Categories Table
-- =====================================================
CREATE TABLE IF NOT EXISTS categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    category_description TEXT,
    category_image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Products Table
-- =====================================================
CREATE TABLE IF NOT EXISTS products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(200) NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    discount_percent INT DEFAULT 0,
    description TEXT,
    material VARCHAR(100),
    color VARCHAR(50),
    sizes VARCHAR(255),
    stock INT DEFAULT 0,
    product_image VARCHAR(255),
    rating DECIMAL(3, 2) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Product Images Table (Gallery)
-- =====================================================
CREATE TABLE IF NOT EXISTS product_images (
    image_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_main BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Cart Table
-- =====================================================
CREATE TABLE IF NOT EXISTS cart (
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Wishlist Table
-- =====================================================
CREATE TABLE IF NOT EXISTS wishlist (
    wishlist_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_wishlist (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Orders Table
-- =====================================================
CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('credit_card', 'debit_card', 'net_banking', 'upi', 'cod') DEFAULT 'cod',
    order_status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(50),
    shipping_phone VARCHAR(15),
    order_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Order Items Table
-- =====================================================
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    product_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    item_total DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Reviews & Ratings Table
-- =====================================================
CREATE TABLE IF NOT EXISTS reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Coupons Table
-- =====================================================
CREATE TABLE IF NOT EXISTS coupons (
    coupon_id INT PRIMARY KEY AUTO_INCREMENT,
    coupon_code VARCHAR(50) UNIQUE NOT NULL,
    discount_percent INT DEFAULT 0,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    min_order_amount DECIMAL(10, 2) DEFAULT 0,
    max_uses INT DEFAULT -1,
    used_count INT DEFAULT 0,
    valid_from DATE,
    valid_till DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Indexes for Performance
-- =====================================================
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_product_category ON products(category_id);
CREATE INDEX idx_cart_user ON cart(user_id);
CREATE INDEX idx_wishlist_user ON wishlist(user_id);
CREATE INDEX idx_order_user ON orders(user_id);
CREATE INDEX idx_review_product ON reviews(product_id);
CREATE INDEX idx_review_user ON reviews(user_id);

-- =====================================================
-- Insert Sample Admin User
-- =====================================================
INSERT INTO admin (admin_name, admin_email, admin_password, admin_phone, role) VALUES 
('Admin User', 'admin@dumbledore.com', '$2y$10$eTxkN5XqVbB5BFE5vPqYBeDT0UmD5sWg6L8Z4hs.q8Z5vK9v9vqZe', '9876543210', 'superadmin');
-- Password: admin@123

-- =====================================================
-- Insert Sample Customer User
-- =====================================================
INSERT INTO users (fullname, email, phone, password, address, city, state, postal_code) VALUES 
('John Doe', 'customer@example.com', '9876543210', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4MqDG', '123 Main Street', 'Mumbai', 'Maharashtra', '400001');
-- Password: password123

-- =====================================================
-- Insert Sample Categories
-- =====================================================
INSERT INTO categories (category_name, category_description, status) VALUES 
('Silk Sarees', 'Premium silk sarees with traditional designs', 'active'),
('Designer Sarees', 'Exclusive designer sarees for special occasions', 'active'),
('Traditional Chudithars', 'Traditional chudithars with elegant patterns', 'active'),
('Festival Collections', 'Festival special collections with stunning designs', 'active'),
('Modern Kurtis', 'Trendy and modern kurtis for everyday wear', 'active'),
('Trendy Dresses', 'Latest trendy dresses for fashion-forward women', 'active'),
('Indo-Western', 'Indo-western fusion fashion collection', 'active'),
('Stylish Outfits', 'Stylish and elegant outfits for all occasions', 'active');

-- =====================================================
-- Insert Sample Products
-- =====================================================
INSERT INTO products (product_name, category_id, price, discount_percent, description, material, color, sizes, stock) VALUES 
('Luxury Gold Silk Saree', 1, 12999, 15, 'Premium silk saree with golden zari work and traditional patterns', 'Pure Silk', 'Gold', 'One Size', 50),
('Royal Blue Designer Saree', 2, 18999, 20, 'Exclusive designer saree perfect for weddings and special events', 'Silk Blend', 'Blue', 'One Size', 30),
('Elegant Cotton Chudidhar', 3, 2999, 10, 'Comfortable cotton chudidhar set with traditional embroidery', 'Cotton', 'White', 'S,M,L,XL', 100),
('Festival Silk Saree', 4, 15999, 25, 'Festival special saree with vibrant colors and intricate designs', 'Silk', 'Red', 'One Size', 40),
('Maroon Modern Kurti', 5, 1899, 5, 'Modern kurti perfect for casual and semi-formal occasions', 'Cotton Blend', 'Maroon', 'S,M,L,XL', 150),
('Black Trendy Dress', 6, 3499, 15, 'Trendy black dress with elegant draping', 'Polyester', 'Black', 'XS,S,M,L,XL', 80),
('Gold Indo-Western', 7, 4999, 20, 'Stylish indo-western fusion outfit with modern touch', 'Silk Blend', 'Gold', 'S,M,L', 60),
('Emerald Stylish Outfit', 8, 5999, 18, 'Elegant outfit perfect for evening wear', 'Silk', 'Emerald', 'S,M,L,XL', 45);

-- =====================================================
-- Insert Sample Reviews
-- =====================================================
INSERT INTO reviews (product_id, user_id, rating, review_text, is_verified) VALUES 
(1, 1, 5, 'Beautiful saree, excellent quality and perfect fit!', TRUE),
(2, 1, 4, 'Great designer saree, highly recommended', TRUE),
(3, 1, 5, 'Comfortable and elegant chudidhar', TRUE);

-- =====================================================
-- Insert Sample Coupon
-- =====================================================
INSERT INTO coupons (coupon_code, discount_percent, min_order_amount, valid_from, valid_till, status) VALUES 
('DUMBLEDORE20', 20, 5000, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'active'),
('WELCOME10', 10, 2000, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 60 DAY), 'active');

-- =====================================================
-- Database Setup Complete
-- =====================================================
