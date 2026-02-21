-- ===========================
-- DATABASE CREATION
-- ===========================
CREATE DATABASE IF NOT EXISTS online_shopping_DB2;
USE online_shopping_DB2;

-- ===========================
-- CUSTOMER TABLE
-- ===========================
CREATE TABLE CUSTOMER (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(100) UNIQUE NOT NULL,
    city        VARCHAR(100) NOT NULL
);

-- ===========================
-- ORDERS TABLE
-- ===========================
CREATE TABLE ORDERS (
    order_id    INT AUTO_INCREMENT PRIMARY KEY,
    order_date  DATE NOT NULL,
    customer_id INT,
    FOREIGN KEY (customer_id) REFERENCES CUSTOMER(customer_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- ===========================
-- PRODUCT TABLE
-- ===========================
CREATE TABLE PRODUCT (
    product_id    INT AUTO_INCREMENT PRIMARY KEY,
    product_name  VARCHAR(150) NOT NULL,
    price         DECIMAL(10,2) NOT NULL
);

-- ===========================
-- ORDER_PRODUCT (M:N RESOLUTION)
-- ===========================
CREATE TABLE ORDER_PRODUCT (
    order_id   INT,
    product_id INT,
    quantity   INT NOT NULL CHECK(quantity > 0),

    PRIMARY KEY(order_id, product_id),

    FOREIGN KEY (order_id) REFERENCES ORDERS(order_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- ===========================
-- PAYMENT TABLE
-- ===========================
CREATE TABLE PAYMENT (
    payment_id    INT AUTO_INCREMENT PRIMARY KEY,
    amount        DECIMAL(10,2) NOT NULL,
    type          VARCHAR(50) NOT NULL,
    payment_date  DATE NOT NULL,
    order_id      INT UNIQUE,   -- ensures 1-to-1 with ORDERS

    FOREIGN KEY (order_id) REFERENCES ORDERS(order_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


USE online_shopping;

-- ===========================
-- POPULATE CUSTOMER TABLE
-- ===========================
INSERT INTO CUSTOMER (name, email, city)
VALUES
('Amit Sharma', 'amit.sharma@example.com', 'Mumbai'),
('Priya Patel', 'priya.patel@example.com', 'Pune'),
('Rahul Mehta', 'rahul.mehta@example.com', 'Delhi'),
('Neha Singh', 'neha.singh@example.com', 'Bangalore'),
('Vikram Rao', 'vikram.rao@example.com', 'Chennai');

-- ===========================
-- POPULATE PRODUCT TABLE
-- ===========================
INSERT INTO PRODUCT (product_name, price)
VALUES
('Wireless Mouse', 799.00),
('Mechanical Keyboard', 2499.00),
('Bluetooth Headphones', 1999.00),
('USB-C Charger', 1299.00),
('Smart Watch', 3499.00),
('Laptop Stand', 999.00),
('HD Webcam', 1799.00),
('Portable SSD 1TB', 6999.00);

-- ===========================
-- POPULATE ORDERS TABLE
-- ===========================
INSERT INTO ORDERS (order_date, customer_id)
VALUES
('2025-11-01', 1),
('2025-11-02', 2),
('2025-11-03', 3),
('2025-11-03', 1),
('2025-11-04', 4),
('2025-11-05', 5);

-- ===========================
-- POPULATE ORDER_PRODUCT TABLE (M:N)
-- ===========================
INSERT INTO ORDER_PRODUCT (order_id, product_id, quantity)
VALUES
(1, 1, 2),   -- Order 1: 2 Wireless Mice
(1, 2, 1),   -- Order 1: 1 Keyboard
(2, 3, 1),   -- Order 2: 1 Headphone
(2, 4, 1),   -- Order 2: 1 Charger
(3, 5, 1),   -- Order 3: 1 Smart Watch
(4, 6, 1),   -- Order 4: 1 Laptop Stand
(4, 8, 1),   -- Order 4: 1 Portable SSD
(5, 7, 1),   -- Order 5: 1 Webcam
(6, 3, 1),   -- Order 6: 1 Headphone
(6, 1, 1);   -- Order 6: 1 Mouse

-- ===========================
-- POPULATE PAYMENT TABLE (1:1)
-- ===========================
INSERT INTO PAYMENT (amount, type, payment_date, order_id)
VALUES
(4097.00, 'Credit Card', '2025-11-01', 1),
(3298.00, 'UPI', '2025-11-02', 2),
(3499.00, 'Debit Card', '2025-11-03', 3),
(7998.00, 'Credit Card', '2025-11-03', 4),
(1799.00, 'Net Banking', '2025-11-04', 5),
(2798.00, 'UPI', '2025-11-05', 6);
