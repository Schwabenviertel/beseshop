# PROJEKTDOKUMENTATION
## Realisierung eines Webauftritts für den Hardware-Shop "BESE.CO"

**Fach:** Webentwicklung (HTML, PHP, SQL)  
**Projektmitglieder:** [Dein Name / Gruppe]  
**Datum:** 03. Mai 2026

---

## Inhaltsverzeichnis
1. Projektbeschreibung
2. Datenbankmodell (Meilenstein 1)
3. Implementierung (Meilenstein 2 & 3)
4. Probleme und Einschränkungen
5. Zukunftsaussichten und Erweiterungen
6. Bedienungsanleitung (XAMPP)

---

## 1. Projektbeschreibung
Das Projekt "BESE.CO" umfasst die Erstellung einer Online-Präsenz für ein schwäbisches Hardware-Geschäft, das sich auf handgefertigte Besen spezialisiert hat. Ziel war es, eine funktionale Website mit Datenbankanbindung zu erstellen, die eine Landing-Page, eine Registrierung für Kunden, eine Produktübersicht und ein Bestellsystem bietet. Wir haben Wert auf eine einfache Bedienung und die Integration des schwäbischen Dialekts gelegt. Die Seite kann aber auch auf Englisch umgestellt werden.

---

## 2. Datenbankmodell
### 2.1 Entity-Relationship-Diagramm (ERD)
Wir haben das Modell mit MySQL-Workbench entworfen. Hier ist der logische Aufbau:

- **CUSTOMERS (Kunden)**
  - id (Primärschlüssel)
  - customer_number (eindeutige Nummer für den Kunden)
  - first_name, last_name, email, password, street, zip_code, city

- **PRODUCTS (Artikel)**
  - id (Primärschlüssel)
  - product_number (eindeutige Artikelnummer)
  - name, description, price, stock

- **ORDERS (Bestellungen)**
  - id (Primärschlüssel)
  - customer_id (Fremdschlüssel zu CUSTOMERS)
  - product_id (Fremdschlüssel zu PRODUCTS)
  - quantity (Menge)
  - order_date (Datum der Bestellung)

**Beziehungen:**
- Ein Kunde kann viele Bestellungen aufgeben (1:n).
- Ein Produkt kann in vielen Bestellungen vorkommen (1:n).

### 2.2 SQL-Abfragen (Beispiele)
Hier sind die wichtigsten Abfragen aus dem Code:

1. **Neuen Kunden anlegen:**
   `INSERT INTO customers (...) VALUES (...);`
2. **Produkte für die Tabelle laden:**
   `SELECT * FROM products;`
3. **Kunden-ID anhand der Nummer finden:**
   `SELECT id FROM customers WHERE customer_number = 'K12345';`
4. **Bestellung speichern:**
   `INSERT INTO orders (customer_id, product_id, quantity) VALUES (1, 5, 2);`

---

## 3. Implementierung
### 3.1 Dateistruktur
- `header.php` & `footer.php`: Das Grundgerüst der Seite (Navigation, Footer).
- `config.php`: Die Verbindung zur Datenbank.
- `translations.php`: Hier stehen alle Texte in Deutsch/Schwäbisch und Englisch.
- `index.php`: Die Startseite.
- `register.php`: Das Formular für neue Kunden.
- `products.php`: Die Liste aller Besen.
- `order.php`: Hier kann man bestellen.
- `style.css`: Alle Design-Einstellungen (Farben, Abstände, Schrift).

### 3.2 Funktionsweise
Die Website nutzt PHP, um Daten aus der MySQL-Datenbank zu lesen und anzuzeigen. Wenn man sich registriert, wird per Zufall eine Kundennummer (K + 5 Zahlen) generiert. In der Bestellung gibt man diese Nummer und die Artikelnummer ein, und PHP verknüpft das dann in der Datenbank.

---

## 4. Probleme und Einschränkungen
- Wir mussten uns erst in PDO (PHP Data Objects) einarbeiten, um die Datenbank sicher anzubinden.
- Das Design mit CSS so hinzubekommen, dass es modern aussieht aber trotzdem zum schwäbischen Thema passt, war knifflig.
- Es gibt noch keine richtige Login-Funktion (nur Registrierung), das wäre der nächste Schritt.

---

## 5. Zukunftsaussichten und Erweiterungen
- Ein richtiger Login mit Passwort-Prüfung.
- Ein Warenkorb, damit man mehrere Besen auf einmal kaufen kann.
- Eine Suchfunktion für die Produkte.
- E-Mail Bestätigung nach der Bestellung.

---

## 6. Bedienungsanleitung (XAMPP)
1. Starte XAMPP (Apache und MySQL).
2. Gehe auf `http://localhost/phpmyadmin/`.
3. Erstelle eine neue Datenbank namens `beseshop`.
4. Importiere die Datei `database.sql`.
5. Kopiere den Projektordner in `C:\xampp\htdocs\beseshop`.
6. Rufe `http://localhost/beseshop/index.php` im Browser auf.
