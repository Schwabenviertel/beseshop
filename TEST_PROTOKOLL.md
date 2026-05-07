# TEST- UND FEHLERPROTOKOLL: BESE.CO Webshop

Dieses Dokument dient der Dokumentation sämtlicher Testphasen, aufgetretener Fehlerfälle sowie des Kommunikationsflusses zwischen Qualitätssicherung, Entwicklung und Projektleitung während der Realisierungsphase.

---

## 1. Projektteam
- **Projektleiter:** Vincent
- **Entwickler:** Angelo
- **Qualitätssicherung (QA):** Julia

---

## 2. Testfall-Übersicht

---

### Testfall TF-001

| | | | |
| :--- | :--- | :--- | :--- |
| **Test Case ID** | TF-001 | **Test Case Description** | Datenbankverbindung & Fehlerbehandlung |
| **Created By** | Julia | **Reviewed By** | Vincent |
| | | **Version** | 1.0 |

| | | | | |
| :--- | :--- | :--- | :--- | :--- |
| **Tester's Name** | Julia | **Date Tested** | 03. Mai 2026 | **Test Case (Pass/Fail/Not Executed)** | Fail |

| S # | Prerequisites | | S # | Test Data |
| :--- | :--- | :--- | :--- | :--- |
| 1 | XAMPP ist installiert und gestartet | | 1 | Keine speziellen Testdaten erforderlich |
| 2 | MySQL-Dienst ist absichtlich deaktiviert | | 2 | |
| 3 | Projekt ist unter htdocs/beseshop/ abgelegt | | 3 | |

**Test Scenario:** Überprüfung der Systemstabilität bei Nichterreichbarkeit des MySQL-Datenbankdienstes.

| Step # | Step Details | Expected Results | Actual Results | Pass / Fail / Not executed / Suspended |
| :--- | :--- | :--- | :--- | :--- |
| 1 | XAMPP starten, MySQL-Dienst deaktiviert lassen | Apache läuft, MySQL ist gestoppt | As Expected | Pass |
| 2 | `http://localhost/beseshop/products.php` im Browser öffnen | Benutzerfreundliche Fehlermeldung wird angezeigt | PHP Fatal Error (Uncaught PDOException), White Screen | Fail |
| 3 | Angelo implementiert Try-Catch in `config.php` | `$pdo` wird auf `null` gesetzt bei Fehler | As Expected | Pass |
| 4 | Seite erneut aufrufen nach Fix | Seite lädt mit Hinweis auf Wartungsarbeiten | As Expected | Pass |

**Ergebnis:** Nach Behebung durch Angelo (Try-Catch-Block in `config.php`) funktioniert die Fehlerbehandlung korrekt. **Status: BEHOBEN**

---

### Testfall TF-002

| | | | |
| :--- | :--- | :--- | :--- |
| **Test Case ID** | TF-002 | **Test Case Description** | Validierung von Datenbank-Einträgen (fehlerhafte Werte) |
| **Created By** | Julia | **Reviewed By** | Vincent |
| | | **Version** | 1.0 |

| | | | | |
| :--- | :--- | :--- | :--- | :--- |
| **Tester's Name** | Julia | **Date Tested** | 03. Mai 2026 | **Test Case (Pass/Fail/Not Executed)** | Fail |

| S # | Prerequisites | | S # | Test Data |
| :--- | :--- | :--- | :--- | :--- |
| 1 | XAMPP läuft (Apache + MySQL) | | 1 | Produkt mit `price = NULL` in der DB |
| 2 | Datenbank `beseshop` ist importiert | | 2 | Bestellmenge: `-5` |
| 3 | Testprodukt mit fehlendem Preis angelegt | | 3 | |

**Test Scenario:** Prüfung der Darstellung und Verarbeitung bei inkonsistenten oder fehlerhaften Werten in der Datenbank.

| Step # | Step Details | Expected Results | Actual Results | Pass / Fail / Not executed / Suspended |
| :--- | :--- | :--- | :--- | :--- |
| 1 | Produkt ohne Preis (NULL) in DB einfügen | Produkt wird in DB gespeichert | As Expected | Pass |
| 2 | `products.php` aufrufen | Preis wird als "0,00 EUR" oder Fallback angezeigt | Leeres Preisfeld, Tabelle sieht kaputt aus | Fail |
| 3 | `order.php` öffnen, Menge auf `-5` setzen | Fehlermeldung, Bestellung wird abgelehnt | Bestellung mit negativer Menge wird akzeptiert | Fail |
| 4 | Angelo implementiert `number_format()` und `min="1"` Validierung | Preis korrekt formatiert, negative Mengen abgelehnt | As Expected | Pass |
| 5 | Erneut testen nach Fix | Preise korrekt, negative Mengen werden blockiert | As Expected | Pass |

**Ergebnis:** Nach Behebung durch Angelo (Formatierung und Validierung) werden fehlerhafte Werte korrekt abgefangen. **Status: BEHOBEN**

---

### Testfall TF-003

| | | | |
| :--- | :--- | :--- | :--- |
| **Test Case ID** | TF-003 | **Test Case Description** | Duplettenprüfung bei der Registrierung |
| **Created By** | Julia | **Reviewed By** | Vincent |
| | | **Version** | 1.0 |

| | | | | |
| :--- | :--- | :--- | :--- | :--- |
| **Tester's Name** | Julia | **Date Tested** | 04. Mai 2026 | **Test Case (Pass/Fail/Not Executed)** | Pass |

| S # | Prerequisites | | S # | Test Data |
| :--- | :--- | :--- | :--- | :--- |
| 1 | XAMPP läuft (Apache + MySQL) | | 1 | E-Mail: `max@mustermann.de` |
| 2 | Datenbank `beseshop` ist importiert | | 2 | Passwort: `passwort123` |
| 3 | Testkunde `max@mustermann.de` existiert bereits | | 3 | Vorname: `Test`, Nachname: `User` |

**Test Scenario:** Sicherstellung, dass E-Mail-Adressen nicht mehrfach registriert werden können.

| Step # | Step Details | Expected Results | Actual Results | Pass / Fail / Not executed / Suspended |
| :--- | :--- | :--- | :--- | :--- |
| 1 | `register.php` im Browser öffnen | Registrierungsformular wird angezeigt | As Expected | Pass |
| 2 | Alle Felder mit Testdaten ausfüllen (bereits existierende E-Mail) | Formular ist ausgefüllt | As Expected | Pass |
| 3 | Auf "Registrieren" klicken | Fehlermeldung: E-Mail bereits vergeben | As Expected | Pass |
| 4 | Registrierung mit neuer E-Mail versuchen | Registrierung erfolgreich, Weiterleitung zum Produktkatalog | As Expected | Pass |

**Ergebnis:** Die Duplikatprüfung funktioniert korrekt über UNIQUE-Constraint und PHP-Prüfung. **Status: ERFOLGREICH**

---

### Testfall TF-004

| | | | |
| :--- | :--- | :--- | :--- |
| **Test Case ID** | TF-004 | **Test Case Description** | End-to-End Bestellprozess |
| **Created By** | Julia | **Reviewed By** | Vincent |
| | | **Version** | 1.0 |

| | | | | |
| :--- | :--- | :--- | :--- | :--- |
| **Tester's Name** | Julia | **Date Tested** | 04. Mai 2026 | **Test Case (Pass/Fail/Not Executed)** | Pass |

| S # | Prerequisites | | S # | Test Data |
| :--- | :--- | :--- | :--- | :--- |
| 1 | XAMPP läuft (Apache + MySQL) | | 1 | Login: `max@mustermann.de` / `passwort123` |
| 2 | Datenbank `beseshop` ist importiert | | 2 | Produkt: Stubenbesen Kehrwisch (B001) |
| 3 | Testkunde ist eingeloggt | | 3 | Menge: 2 |
| 4 | Produkte sind auf Lager | | 4 | Zahlungsmethode: Rechnung |

**Test Scenario:** Vollständiger Durchlauf einer Bestellung von der Artikelauswahl bis zur Speicherung in der Datenbank.

| Step # | Step Details | Expected Results | Actual Results | Pass / Fail / Not executed / Suspended |
| :--- | :--- | :--- | :--- | :--- |
| 1 | Mit Testdaten einloggen | Login erfolgreich, Weiterleitung zum Produktkatalog | As Expected | Pass |
| 2 | Auf "Produkte" klicken | Produktkatalog wird mit allen 5 Produkten angezeigt | As Expected | Pass |
| 3 | Bei "Stubenbesen Kehrwisch" auf "In den Warenkorb" klicken | Produkt wird zum Warenkorb hinzugefügt, Badge zeigt "1" | As Expected | Pass |
| 4 | Warenkorb öffnen und "Zur Kasse" klicken | Checkout-Seite mit Warenkorb-Artikeln wird angezeigt | As Expected | Pass |
| 5 | Zahlungsmethode "Rechnung" auswählen | Karte wird visuell hervorgehoben | As Expected | Pass |
| 6 | Auf "Jetzt bestellen" klicken | Bestellung wird gespeichert, Bestätigungsseite erscheint | As Expected | Pass |
| 7 | In der Datenbank prüfen (phpMyAdmin) | Neuer Eintrag in `orders`-Tabelle mit korrekten FK-Beziehungen | As Expected | Pass |

**Ergebnis:** Der gesamte Bestellprozess funktioniert einwandfrei. FK-Beziehungen sind korrekt. **Status: ERFOLGREICH**

---

### Testfall TF-005

| | | | |
| :--- | :--- | :--- | :--- |
| **Test Case ID** | TF-005 | **Test Case Description** | Checkout-Prozess mit Zahlungsmethode |
| **Created By** | Julia | **Reviewed By** | Vincent |
| | | **Version** | 1.0 |

| | | | | |
| :--- | :--- | :--- | :--- | :--- |
| **Tester's Name** | Julia | **Date Tested** | 05. Mai 2026 | **Test Case (Pass/Fail/Not Executed)** | Pass |

| S # | Prerequisites | | S # | Test Data |
| :--- | :--- | :--- | :--- | :--- |
| 1 | XAMPP läuft (Apache + MySQL) | | 1 | Login: `anna@schmidt.de` / `passwort123` |
| 2 | Datenbank `beseshop` ist importiert | | 2 | Produkt: Industriebesen Meister (B004) |
| 3 | Testkunde ist eingeloggt | | 3 | Menge: 1 |
| 4 | | | 4 | Zahlungsmethoden: Rechnung, PayPal, Kreditkarte, Vorkasse |

**Test Scenario:** Vollständiger Test des dreistufigen Checkout-Prozesses mit Produktauswahl, Zahlungsmethode und Bestellzusammenfassung.

| Step # | Step Details | Expected Results | Actual Results | Pass / Fail / Not executed / Suspended |
| :--- | :--- | :--- | :--- | :--- |
| 1 | Über "Bestellen" in der Navigation den Einzelprodukt-Checkout öffnen | Produktauswahl per Dropdown wird angezeigt | As Expected | Pass |
| 2 | Produkt "Industriebesen Meister" im Dropdown auswählen | Preis und Verfügbarkeit werden live aktualisiert | As Expected | Pass |
| 3 | Zahlungsmethode "PayPal" auswählen | PayPal-Karte wird visuell hervorgehoben | As Expected | Pass |
| 4 | Zusammenfassung prüfen | Einzelpreis, Menge und Gesamtbetrag korrekt | As Expected | Pass |
| 5 | Auf "Jetzt bestellen" klicken | Bestellung gespeichert, Lagerbestand reduziert | As Expected | Pass |
| 6 | Bestätigungsseite prüfen | Lieferadresse, Kundendaten, Zahlungsmethode korrekt | As Expected | Pass |
| 7 | Vorgang mit allen 4 Zahlungsmethoden wiederholen | Alle Zahlungsmethoden werden korrekt gespeichert | As Expected | Pass |

**Ergebnis:** Der Checkout funktioniert mit allen vier Zahlungsmethoden einwandfrei. Live-Zusammenfassung aktualisiert sich korrekt. **Status: ERFOLGREICH**

---

### Testfall TF-006

| | | | |
| :--- | :--- | :--- | :--- |
| **Test Case ID** | TF-006 | **Test Case Description** | Bestellstornierung mit Lagerbestandswiederherstellung |
| **Created By** | Julia | **Reviewed By** | Vincent |
| | | **Version** | 1.0 |

| | | | | |
| :--- | :--- | :--- | :--- | :--- |
| **Tester's Name** | Julia | **Date Tested** | 05. Mai 2026 | **Test Case (Pass/Fail/Not Executed)** | Pass |

| S # | Prerequisites | | S # | Test Data |
| :--- | :--- | :--- | :--- | :--- |
| 1 | XAMPP läuft (Apache + MySQL) | | 1 | Login: `max@mustermann.de` / `passwort123` |
| 2 | Datenbank `beseshop` ist importiert | | 2 | Bestehende Bestellung vorhanden |
| 3 | Testkunde ist eingeloggt | | 3 | Lagerbestand vor Stornierung notieren |
| 4 | Mindestens eine Bestellung existiert | | 4 | |

**Test Scenario:** Prüfung der Stornierungsfunktion — Bestellung soll gelöscht und der Lagerbestand wiederhergestellt werden.

| Step # | Step Details | Expected Results | Actual Results | Pass / Fail / Not executed / Suspended |
| :--- | :--- | :--- | :--- | :--- |
| 1 | "Meine Bestellungen" in der Navigation öffnen | Bestellübersicht mit allen Bestellungen wird angezeigt | As Expected | Pass |
| 2 | Lagerbestand des bestellten Produkts in phpMyAdmin notieren | Aktueller Bestand ist sichtbar | As Expected | Pass |
| 3 | Auf "Stornieren" bei einer Bestellung klicken | Bestätigungsdialog erscheint | As Expected | Pass |
| 4 | Stornierung bestätigen | Bestellung wird gelöscht, Erfolgsmeldung erscheint | As Expected | Pass |
| 5 | Bestellübersicht erneut prüfen | Stornierte Bestellung ist nicht mehr sichtbar | As Expected | Pass |
| 6 | Lagerbestand in phpMyAdmin prüfen | Bestand wurde um die stornierte Menge erhöht | As Expected | Pass |
| 7 | Versuch, Bestellung eines anderen Kunden zu stornieren | Stornierung wird abgelehnt (nur eigene Bestellungen) | As Expected | Pass |

**Ergebnis:** Stornierung funktioniert korrekt. Lagerbestand wird wiederhergestellt, nur eigene Bestellungen können storniert werden. **Status: ERFOLGREICH**

---

## 3. Kommunikationslogbuch

| Datum | Von | An | Gegenstand | Ergebnis |
| :--- | :--- | :--- | :--- | :--- |
| 03.05.2026 | Julia | Angelo | Schwerwiegender Fehler bei DB-Ausfall | Try-Catch Logik in `config.php` implementiert. |
| 03.05.2026 | Julia | Angelo | Ungültige Werte in DB (Preise/Mengen) | Validierung und Fallback-Werte hinzugefügt. |
| 04.05.2026 | Vincent | Team | Finales Review der Dokumentation | Abnahme des ERM-Diagramms und der technischen Berichte. |
| 04.05.2026 | Julia | Angelo | Optisches Feedback bei Bestellung | Erfolgs- und Fehlermeldungen in `order.php` hervorgehoben. |
| 05.05.2026 | Julia | Angelo | Checkout-Prozess und Zahlungsmethoden | Dreistufiger Checkout mit Live-Zusammenfassung implementiert. |
| 05.05.2026 | Julia | Angelo | Bestellstornierung testen | Stornierung mit Lagerbestandswiederherstellung funktioniert. |
