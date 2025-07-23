-- SQLite initialization script
-- Adapted from MySQL init.sql

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
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    price REAL NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE stock (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    variation TEXT NOT NULL,
    quantity INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE coupons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    discount_type TEXT NOT NULL DEFAULT 'percentage' CHECK (discount_type IN ('percentage', 'fixed')),
    discount_value REAL NOT NULL,
    min_amount REAL DEFAULT 0,
    max_uses INTEGER DEFAULT NULL,
    used_count INTEGER DEFAULT 0,
    valid_from DATE NOT NULL,
    valid_until DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name TEXT NOT NULL,
    customer_email TEXT NOT NULL,
    customer_phone TEXT,
    customer_document TEXT,
    shipping_address TEXT NOT NULL,
    shipping_number TEXT,
    shipping_complement TEXT,
    shipping_neighborhood TEXT,
    shipping_city TEXT NOT NULL,
    shipping_state TEXT NOT NULL,
    shipping_zipcode TEXT NOT NULL,
    subtotal REAL NOT NULL,
    shipping_cost REAL DEFAULT 0,
    discount_amount REAL DEFAULT 0,
    total REAL NOT NULL,
    coupon_id INTEGER DEFAULT NULL,
    coupon_code TEXT DEFAULT NULL,
    status TEXT DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled')),
    payment_status TEXT DEFAULT 'pending' CHECK (payment_status IN ('pending', 'paid', 'failed', 'refunded')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id),
    FOREIGN KEY (coupon_code) REFERENCES coupons(code)
);

CREATE TABLE order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    product_name TEXT NOT NULL,
    variation TEXT,
    quantity INTEGER NOT NULL,
    unit_price REAL NOT NULL,
    total_price REAL NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE order_status_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    old_status TEXT,
    new_status TEXT NOT NULL,
    changed_by TEXT DEFAULT 'system',
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE webhook_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    old_status TEXT,
    new_status TEXT NOT NULL,
    webhook_data TEXT,
    processed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN DEFAULT TRUE,
    error_message TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Insert some sample coupons
INSERT INTO coupons (code, discount_type, discount_value, min_amount, max_uses, valid_from, valid_until, is_active) VALUES
('BEMVINDO10', 'percentage', 10.00, 50.00, 100, date('now'), date('now', '+30 days'), 1),
('ECONOMIZE20', 'fixed', 20.00, 100.00, 50, date('now'), date('now', '+60 days'), 1),
('PRIMEIRO15', 'percentage', 15.00, 75.00, 200, date('now'), date('now', '+45 days'), 1),
('MEGA30', 'percentage', 30.00, 200.00, 25, date('now'), date('now', '+15 days'), 1),
('FRETE5', 'fixed', 5.00, 30.00, NULL, date('now'), date('now', '+90 days'), 1);

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
(5, 'G - Floral', 6);

-- Create trigger to update updated_at column in orders table
CREATE TRIGGER update_orders_updated_at 
    AFTER UPDATE ON orders
    FOR EACH ROW
    WHEN NEW.updated_at = OLD.updated_at
BEGIN
    UPDATE orders SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;