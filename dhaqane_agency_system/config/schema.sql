CREATE DATABASE IF NOT EXISTS dhaqane_db;
USE dhaqane_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    passport_no VARCHAR(50),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS travel_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    type ENUM('Flight', 'Visa', 'Hotel', 'Other') NOT NULL,
    description TEXT,
    flight_date DATE,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cargo_shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_name VARCHAR(100) NOT NULL,
    receiver_phone VARCHAR(20) NOT NULL,
    tracking_no VARCHAR(50) UNIQUE NOT NULL,
    weight DECIMAL(10, 2) NOT NULL,
    price_per_kg DECIMAL(10, 2) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Shipped', 'Arrived', 'Delivered') DEFAULT 'Pending',
    shipped_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES customers(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    related_id INT NOT NULL, -- Booking ID or Shipment ID
    related_type ENUM('Travel', 'Cargo') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('Cash', 'Bank Transfer', 'Mobile Money') NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin User (Password: admin123)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
