-- ============================================
--                           _  __            _
--  _ __ ___  _   _ ___  ___(_)/ _| __ _ _ __(_)
-- | '_ ` _ \| | | / __|/ __| | |_ / _` | '__| |
-- | | | | | | |_| \__ \ (__| |  _| (_| | |  | |
-- |_| |_| |_|\__,_|___/\___|_|_|  \__,_|_|  |_|
--
-- Datenbank-Schema für den BESE.CO Webshop
-- ============================================
-- Anleitung:
-- 1. XAMPP starten (Apache + MySQL)
-- 2. phpMyAdmin öffnen (http://localhost/phpmyadmin)
-- 3. Diese Datei importieren (Tab "Importieren")
-- ============================================

-- Datenbank erstellen
CREATE DATABASE IF NOT EXISTS beseshop;
USE beseshop;

-- ============================================
-- Tabelle: customers (Kunden)
-- Speichert die Daten registrierter Kunden.
-- ============================================
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_number VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    street VARCHAR(100),
    zip_code VARCHAR(10),
    city VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Tabelle: products (Produkte/Artikel)
-- Speichert alle angebotenen Besen mit Preis und Bestand.
-- ============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_number VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0
);

-- ============================================
-- Tabelle: orders (Bestellungen)
-- Verknüpft Kunden mit Produkten (Beziehungstabelle).
-- Fremdschlüssel verweisen auf customers und products.
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    payment_method VARCHAR(50) NOT NULL DEFAULT 'Rechnung',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- ============================================
-- Beispieldaten: Produkte
-- ============================================
INSERT INTO products (product_number, name, description, price, stock) VALUES
('B001', 'Stubenbesen Kehrwisch', 'Der Klassiker für die gute Stube. Handgefertigt aus echtem Rosshaar.', 24.99, 12),
('B002', 'Straßenbesen Grober Heiner', 'Für den groben Dreck vor der Haustür. Stabil und langlebig.', 19.50, 8),
('B003', 'Handfeger Zackig', 'Klein aber fein! Ideal für die kleinen Ecken.', 9.99, 25),
('B004', 'Industriebesen Meister', 'Für Werkstatt und Halle. Extra breiter Besenkopf.', 34.99, 5),
('B005', 'Kinderbesen Mini', 'Der kleine Helfer für die Kleinen. Kindgerechte Größe.', 12.99, 15);

-- ============================================
-- Beispieldaten: Kunden
-- Passwort ist "passwort123" (gehasht mit password_hash)
-- ============================================
INSERT INTO customers (customer_number, first_name, last_name, email, password, street, zip_code, city) VALUES
('K10001', 'Max', 'Mustermann', 'max@mustermann.de', '$2y$12$PrD7KxENP4tHCsiNjvw3U.zfQQoxMFQ/KMc2AAVkJJR8jxYNY7mFy', 'Musterstraße 1', '70173', 'Stuttgart'),
('K10002', 'Anna', 'Schmidt', 'anna@schmidt.de', '$2y$12$PrD7KxENP4tHCsiNjvw3U.zfQQoxMFQ/KMc2AAVkJJR8jxYNY7mFy', 'Hauptstraße 5', '80331', 'München');

-- ============================================
-- Beispiel SQL-Abfragen (für Dokumentation/Tests)
-- ============================================

-- Alle Produkte anzeigen:
-- SELECT * FROM products;

-- Alle Kunden anzeigen:
-- SELECT * FROM customers;

-- Alle Bestellungen mit Kunden- und Produktnamen anzeigen:
-- SELECT o.id, c.first_name, c.last_name, c.customer_number, 
--        p.name, p.product_number, o.quantity, o.order_date
-- FROM orders o
-- JOIN customers c ON o.customer_id = c.id
-- JOIN products p ON o.product_id = p.id;

-- Bestellungen eines bestimmten Kunden:
-- SELECT * FROM orders WHERE customer_id = 1;
