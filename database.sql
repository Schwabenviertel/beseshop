-- Datenbank Script für den BESE.CO Webshop
-- Erstellt die Datenbank 'beseshop', falls sie noch nicht existiert
CREATE DATABASE IF NOT EXISTS beseshop;
-- Wechselt zur Verwendung der Datenbank 'beseshop'
USE beseshop;

-- Tabelle für die Kundeninformationen
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Eindeutige interne ID, zählt automatisch hoch
    customer_number VARCHAR(20) UNIQUE NOT NULL, -- Eindeutige Kundennummer (z.B. K12345)
    first_name VARCHAR(50) NOT NULL, -- Vorname des Kunden
    last_name VARCHAR(50) NOT NULL, -- Nachname des Kunden
    email VARCHAR(100) UNIQUE NOT NULL, -- E-Mail Adresse (darf nur einmal vorkommen)
    password VARCHAR(255) NOT NULL, -- Sicher verschlüsseltes Passwort
    street VARCHAR(100), -- Straße und Hausnummer
    zip_code VARCHAR(10), -- Postleitzahl
    city VARCHAR(50), -- Wohnort
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Zeitpunkt der Registrierung
);

-- Tabelle für die Produkte (Besen)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Eindeutige interne ID
    product_number VARCHAR(20) UNIQUE NOT NULL, -- Eindeutige Artikelnummer (z.B. B001)
    name VARCHAR(100) NOT NULL, -- Name des Besens
    description TEXT, -- Ausführliche Beschreibung des Artikels
    price DECIMAL(10, 2) NOT NULL, -- Preis mit 2 Nachkommastellen (z.B. 24.99)
    stock INT DEFAULT 0, -- Aktueller Lagerbestand
    image_url VARCHAR(255) -- Pfad zu einem Produktbild (optional)
);

-- Tabelle für die Bestellungen (verknüpft Kunden und Produkte)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Eindeutige interne ID der Bestellung
    customer_id INT NOT NULL, -- Verweis auf die ID in der Kunden-Tabelle
    product_id INT NOT NULL, -- Verweis auf die ID in der Produkt-Tabelle
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Zeitpunkt der Bestellung
    quantity INT DEFAULT 1, -- Bestelle Menge (standardmäßig 1)
    FOREIGN KEY (customer_id) REFERENCES customers(id), -- Stellt sicher, dass der Kunde existiert
    FOREIGN KEY (product_id) REFERENCES products(id) -- Stellt sicher, dass das Produkt existiert
);

-- Einfügen von initialen Testdaten für die Produktpalette
INSERT INTO products (product_number, name, description, price, stock, image_url) VALUES
-- Erster Besen: Stubenbesen
('B001', 'Stubenbese "Kehrwisch"', 'Der Klassiker für die gute Stube. Handgefertigt aus echtem Rosshaar.', 24.99, 50, ''),
-- Zweiter Besen: Straßenbesen
('B002', 'Straßenbesen "Grober Heiner"', 'Für den groben Dreck vor der Haustür. Stabil und langlebig.', 19.50, 30, ''),
-- Dritter Artikel: Handfeger
('B003', 'Handfeger "Zackig"', 'Klein, aber oho! Ideal für die kleinen Ecken.', 9.99, 100, '');

-- Einfügen eines Beispiel-Kunden für Testzwecke
INSERT INTO customers (customer_number, first_name, last_name, email, password, street, zip_code, city) VALUES
-- Ein schwäbischer Testkunde mit bereits verschlüsseltem Passwort (Standard 'password')
('K10001', 'Hannes', 'Schwob', 'hannes@schwob.de', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Spätzleweg 1', '70173', 'Stuttgart');
