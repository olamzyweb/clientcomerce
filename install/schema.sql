CREATE TABLE IF NOT EXISTS `store_info` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `logo` VARCHAR(255),
  `phone` VARCHAR(20),
  `whatsapp` VARCHAR(20),
  `email` VARCHAR(255),
  `address` TEXT,
  `delivery_areas` TEXT,
  `bank_name` VARCHAR(100),
  `account_number` VARCHAR(50),
  `account_name` VARCHAR(255),
  `primary_color` VARCHAR(20) DEFAULT '#832232'
);

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT,
  `price` DECIMAL(12, 2) NOT NULL,
  `old_price` DECIMAL(12, 2),
  `category_id` INT,
  `stock_status` ENUM('in_stock', 'out_of_stock') DEFAULT 'in_stock',
  `featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS `product_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `is_primary` TINYINT(1) DEFAULT 0,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_name` VARCHAR(255) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `items_json` TEXT NOT NULL,
  `total` DECIMAL(12, 2) NOT NULL,
  `status` ENUM('pending', 'seen', 'completed', 'cancelled') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `settings` (
  `key_name` VARCHAR(50) PRIMARY KEY,
  `key_value` TEXT
);

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL
);
