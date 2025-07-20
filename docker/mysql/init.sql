-- Set charset and collation for proper UTF-8 support
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Drop tables if they exist (in reverse order due to foreign key constraints)
DROP TABLE IF EXISTS webhook_logs;
DROP TABLE IF EXISTS order_status_history;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS stock;
DROP TABLE IF EXISTS coupons;
DROP TABLE IF EXISTS products;

-- Create tables
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    variation VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_type ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    discount_value DECIMAL(10,2) NOT NULL,
    min_amount DECIMAL(10,2) DEFAULT 0,
    max_uses INT DEFAULT NULL,
    used_count INT DEFAULT 0,
    valid_from DATE NOT NULL,
    valid_until DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    customer_document VARCHAR(20),
    shipping_address TEXT NOT NULL,
    shipping_number VARCHAR(20),
    shipping_complement VARCHAR(255),
    shipping_neighborhood VARCHAR(100),
    shipping_city VARCHAR(100) NOT NULL,
    shipping_state VARCHAR(50) NOT NULL,
    shipping_zipcode VARCHAR(20) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping_cost DECIMAL(10,2) DEFAULT 0,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    coupon_id INT DEFAULT NULL,
    coupon_code VARCHAR(50) DEFAULT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id),
    FOREIGN KEY (coupon_code) REFERENCES coupons(code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    variation VARCHAR(255),
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by VARCHAR(100) DEFAULT 'system',
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE webhook_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    webhook_data TEXT,
    processed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN DEFAULT TRUE,
    error_message TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert some sample coupons
INSERT INTO coupons (code, discount_type, discount_value, min_amount, max_uses, valid_from, valid_until, is_active) VALUES
('WELCOME10', 'percentage', 10.00, 50.00, 100, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), TRUE),
('SAVE20', 'fixed', 20.00, 100.00, 50, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 60 DAY), TRUE),
('FIRST15', 'percentage', 15.00, 75.00, 200, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 45 DAY), TRUE),
('MEGA30', 'percentage', 30.00, 200.00, 25, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY), TRUE),
('FRETE5', 'fixed', 5.00, 30.00, NULL, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 90 DAY), TRUE);

-- Insert some sample products
INSERT INTO products (name, price) VALUES
('Camiseta Básica', 29.90),
('Calça Jeans', 89.90),
('Tênis Esportivo', 159.90),
('Jaqueta de Couro', 299.90),
('Vestido Floral', 79.90);

-- Insert sample stock for products
INSERT INTO stock (product_id, variation, quantity) VALUES
(1, 'P - Branco', 10),
(1, 'M - Branco', 15),
(1, 'G - Branco', 8),
(1, 'P - Preto', 12),
(1, 'M - Preto', 20),
(1, 'G - Preto', 5),
(2, '38 - Azul', 7),
(2, '40 - Azul', 10),
(2, '42 - Azul', 6),
(2, '38 - Preto', 8),
(2, '40 - Preto', 12),
(2, '42 - Preto', 4),
(3, '38 - Branco', 5),
(3, '40 - Branco', 8),
(3, '42 - Branco', 3),
(3, '38 - Preto', 6),
(3, '40 - Preto', 10),
(3, '42 - Preto', 2),
(4, 'P - Marrom', 3),
(4, 'M - Marrom', 5),
(4, 'G - Marrom', 2),
(4, 'P - Preto', 4),
(4, 'M - Preto', 6),
(4, 'G - Preto', 1),
(5, 'P - Floral', 8),
(5, 'M - Floral', 12),
(5, 'G - Floral', 6);}]}}}
