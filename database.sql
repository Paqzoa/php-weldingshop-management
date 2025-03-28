-- we need to create the database that we will be using in this case

-- lets name our database as stock-management

-- in the stock-management we first need the users table that will enable users to register themselves
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);




CREATE TABLE `users` (
  `userid` int(11) AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
  `verification` ENUM('Verified', 'Not Verified') NOT NULL DEFAULT 'Not Verified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    order_type VARCHAR(50) NOT NULL,
    requested_delivery_date DATE NOT NULL,
    cost_incurred DECIMAL(10, 2),
    order_description TEXT NOT NULL,
    delivery_status ENUM('Delivered', 'Not Delivered') NOT NULL DEFAULT 'Not Delivered',
    delivery_date DATE, -- New column for delivery date and time
    order_request_date DATETIME NOT NULL, -- New column for order request date and time
    profit DECIMAL(10, 2), -- New column for profit
    percentage_profit DECIMAL(5, 2), -- New column for percentage profit
    final_fee DECIMAL(10, 2) DEFAULT 0.00
);

CREATE TABLE cost (
    cost_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    cost_description VARCHAR(255) NOT NULL,
    cost_amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);


