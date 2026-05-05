-- ============================================
-- Datenbank-Schema fuer den BESE.CO Webshop
-- ============================================
-- Anleitung:
-- 1. XAMPP starten (Apache + MySQL)
-- 2. phpMyAdmin oeffnen (http://localhost/phpmyadmin)
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
-- Verknuepft Kunden mit Produkten (Beziehungstabelle).
-- Fremdschluessel verweisen auf customers und products.
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- ============================================
-- Beispieldaten: Produkte
-- ============================================
INSERT INTO products (product_number, name, description, price, stock) VALUES
('B001', 'Stubenbesen Kehrwisch', 'Der Klassiker fuer die gute Stube. Handgefertigt aus echtem Rosshaar.', 24.99, 50),
('B002', 'Strassenbesen Grober Heiner', 'Fuer den groben Dreck vor der Haustuer. Stabil und langlebig.', 19.50, 30),
('B003', 'Handfeger Zackig', 'Klein aber fein! Ideal fuer die kleinen Ecken.', 9.99, 100),
('B004', 'Industriebesen Meister', 'Fuer Werkstatt und Halle. Extra breiter Besenkopf.', 34.99, 20),
('B005', 'Kinderbesen Mini', 'Der kleine Helfer fuer die Kleinen. Kindgerechte Groesse.', 12.99, 40);

-- ============================================
-- Beispieldaten: Kunden
-- Passwort ist "passwort123" (gehasht mit password_hash)
-- ============================================
INSERT INTO customers (customer_number, first_name, last_name, email, password, street, zip_code, city) VALUES
('K10001', 'Max', 'Mustermann', 'max@mustermann.de', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Musterstrasse 1', '70173', 'Stuttgart'),
('K10002', 'Anna', 'Schmidt', 'anna@schmidt.de', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Hauptstrasse 5', '80331', 'Muenchen');

-- ============================================
-- Beispiel SQL-Abfragen (fuer Dokumentation/Tests)
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
