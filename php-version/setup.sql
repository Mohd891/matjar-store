-- setup.sql
-- Run this file in phpMyAdmin to create the database and tables for Matjar

-- Re-running this file recreates the tables from scratch

-- Create the database
CREATE DATABASE IF NOT EXISTS matjardb CHARACTER SET utf8mb4;
USE matjardb;

DROP TABLE IF EXISTS reviews, order_items, orders, customers, products;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(100),
    specs TEXT
);

-- Create customers table
CREATE TABLE IF NOT EXISTS customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(50) NOT NULL,
    lname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    pswd VARCHAR(255) NOT NULL,
    phone VARCHAR(20)
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    vat DECIMAL(10,2),
    grand_total DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'Pending'
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    product_name VARCHAR(100),
    price DECIMAL(10,2),
    quantity INT
);

-- Create reviews table
CREATE TABLE IF NOT EXISTS reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    customer_name VARCHAR(100),
    rating INT,
    comment TEXT,
    review_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample products
INSERT INTO products (name, category, price, description, image, specs) VALUES
('Smartphone Pro X', 'Electronics', 2999.00, 'The Smartphone Pro X is the latest flagship smartphone from Matjar. It features a stunning display, powerful processor, and an amazing camera system.', 'smartphone.jpg', 'Display: 6.2 inch AMOLED | Processor: Octa-core 3.3 GHz | RAM: 8 GB | Storage: 256 GB | Camera: 50 MP | Battery: 4000 mAh'),

('UltraBook 15 Laptop', 'Electronics', 3499.00, 'The UltraBook 15 Laptop is perfect for students and professionals. It comes with a fast processor, enough storage, and a comfortable keyboard for daily use.', 'laptop.jpg', 'Display: 15.6 inch Full HD | Processor: Intel Core i5-1335U | RAM: 16 GB | Storage: 512 GB SSD | Graphics: Intel Iris Xe | Battery: Up to 8 hours'),

('Classic T-Shirt', 'Fashion', 89.00, 'A comfortable and stylish classic t-shirt made from 100% cotton. Available in multiple colors and sizes. Perfect for everyday wear.', 'tshirt.jpg', 'Material: 100% Cotton | Colors: Black, White, Navy, Grey | Sizes: S, M, L, XL, XXL | Fit: Regular | Care: Machine Washable'),

('Kitchen Blender', 'Home and Kitchen', 249.00, 'A powerful kitchen blender with multiple speed settings. Perfect for making smoothies, juices, and soups. Easy to clean and durable.', 'blender.jpg', 'Power: 700 Watts | Capacity: 1.5 Liters | Speed: 3 Speeds + Pulse | Material: BPA-Free Plastic | Warranty: 2 Years');

-- Insert sample reviews
INSERT INTO reviews (product_id, customer_name, rating, comment) VALUES
(1, 'Ahmed', 5, 'Great phone with amazing camera quality!'),
(1, 'Sara', 4, 'Fast delivery and the phone works perfectly.'),
(2, 'Khalid', 5, 'Great laptop for university work and studying.'),
(2, 'Fatima', 4, 'Fast and reliable. I use it every day.'),
(3, 'Ali', 5, 'Very comfortable and good quality material.'),
(3, 'Nora', 4, 'Bought 3 colors. They all fit perfectly!'),
(4, 'Maha', 5, 'Makes great smoothies! Very powerful motor.'),
(4, 'Yousef', 4, 'Easy to use and clean. Good for the kitchen.');
