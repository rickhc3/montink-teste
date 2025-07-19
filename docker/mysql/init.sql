CREATE TABLE IF NOT EXISTS products (
                                        id INT AUTO_INCREMENT PRIMARY KEY,
                                        name VARCHAR(255),
    price DECIMAL(10,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE IF NOT EXISTS stock (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     product_id INT,
                                     variation VARCHAR(255),
    quantity INT,
    FOREIGN KEY (product_id) REFERENCES products(id)
    );

CREATE TABLE IF NOT EXISTS coupons (
                                       id INT AUTO_INCREMENT PRIMARY KEY,
                                       code VARCHAR(50),
    discount DECIMAL(10,2),
    min_amount DECIMAL(10,2),
    valid_until DATE
    );

CREATE TABLE IF NOT EXISTS orders (
                                      id INT AUTO_INCREMENT PRIMARY KEY,
                                      total DECIMAL(10,2),
    status VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
