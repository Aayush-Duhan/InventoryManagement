-- Insert sample products
INSERT INTO products (name, category, price, quantity, description) VALUES
('iPhone 13', 'Electronics', 999.99, 50, 'Latest iPhone model'),
('Samsung TV', 'Electronics', 799.99, 30, '55-inch Smart TV'),
('Nike Shoes', 'Clothing', 89.99, 100, 'Running shoes'),
('Dell Laptop', 'Electronics', 1299.99, 25, 'Business laptop'),
('Cotton T-Shirt', 'Clothing', 19.99, 200, 'Basic cotton t-shirt'),
('The Great Gatsby', 'Books', 9.99, 150, 'Classic novel'),
('Coffee Maker', 'Electronics', 79.99, 40, 'Automatic coffee maker'),
('Jeans', 'Clothing', 49.99, 75, 'Blue denim jeans'),
('Headphones', 'Electronics', 149.99, 60, 'Wireless headphones'),
('Protein Bars', 'Food', 29.99, 300, 'Box of protein bars');

-- Insert sample customers
INSERT INTO customers (name, email, phone, address) VALUES
('John Doe', 'john@example.com', '123-456-7890', '123 Main St'),
('Jane Smith', 'jane@example.com', '234-567-8901', '456 Oak Ave'),
('Bob Johnson', 'bob@example.com', '345-678-9012', '789 Pine Rd'),
('Alice Brown', 'alice@example.com', '456-789-0123', '321 Elm St'),
('Charlie Wilson', 'charlie@example.com', '567-890-1234', '654 Maple Dr');

-- Insert sample sales (last 30 days of data)
INSERT INTO sales (product_id, customer_id, quantity, total_amount, sale_date) VALUES
(1, 1, 2, 1999.98, DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)),
(2, 2, 1, 799.99, DATE_SUB(CURRENT_DATE, INTERVAL 2 DAY)),
(3, 3, 3, 269.97, DATE_SUB(CURRENT_DATE, INTERVAL 3 DAY)),
(4, 4, 1, 1299.99, DATE_SUB(CURRENT_DATE, INTERVAL 4 DAY)),
(5, 5, 5, 99.95, DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY)),
(6, 1, 2, 19.98, DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY)),
(7, 2, 1, 79.99, DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)),
(8, 3, 2, 99.98, DATE_SUB(CURRENT_DATE, INTERVAL 8 DAY)),
(9, 4, 1, 149.99, DATE_SUB(CURRENT_DATE, INTERVAL 9 DAY)),
(10, 5, 3, 89.97, DATE_SUB(CURRENT_DATE, INTERVAL 10 DAY)),
(1, 1, 1, 999.99, DATE_SUB(CURRENT_DATE, INTERVAL 11 DAY)),
(2, 2, 2, 1599.98, DATE_SUB(CURRENT_DATE, INTERVAL 12 DAY)),
(3, 3, 1, 89.99, DATE_SUB(CURRENT_DATE, INTERVAL 13 DAY)),
(4, 4, 1, 1299.99, DATE_SUB(CURRENT_DATE, INTERVAL 14 DAY)),
(5, 5, 3, 59.97, DATE_SUB(CURRENT_DATE, INTERVAL 15 DAY));

-- Add more recent sales
INSERT INTO sales (product_id, customer_id, quantity, total_amount, sale_date) VALUES
(1, 1, 1, 999.99, CURRENT_DATE),
(2, 2, 1, 799.99, CURRENT_DATE),
(3, 3, 2, 179.98, CURRENT_DATE),
(4, 4, 1, 1299.99, DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)),
(5, 5, 4, 79.96, DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY));

-- Add sales for different categories to show in reports
INSERT INTO sales (product_id, customer_id, quantity, total_amount, sale_date) VALUES
(1, 1, 2, 1999.98, DATE_SUB(CURRENT_DATE, INTERVAL 16 DAY)), -- Electronics
(3, 2, 3, 269.97, DATE_SUB(CURRENT_DATE, INTERVAL 17 DAY)),  -- Clothing
(6, 3, 5, 49.95, DATE_SUB(CURRENT_DATE, INTERVAL 18 DAY)),   -- Books
(10, 4, 4, 119.96, DATE_SUB(CURRENT_DATE, INTERVAL 19 DAY)), -- Food
(7, 5, 2, 159.98, DATE_SUB(CURRENT_DATE, INTERVAL 20 DAY));  -- Electronics 