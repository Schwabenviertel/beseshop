# TEST- UND FEHLERPROTOKOLL: BESE.CO Webshop

Dieses Dokument dient der Dokumentation sämtlicher Testphasen, aufgetretener Fehlerfälle sowie des Kommunikationsflusses zwischen Qualitätssicherung, Entwicklung und Projektleitung während der Realisierungsphase.

---

## 1. Projektteam
- **Projektleiter:** Vincent
- **Entwickler:** Angelo
- **Qualitätssicherung (QA):** Julia

---

## 2. Testfall-Übersicht

### Testfall TF-001: Datenbankverbindung & Fehlerbehandlung
- **Datum:** 03. Mai 2026
- **Prüfer:** Julia
- **Status:** BEHOBEN

**Beschreibung:**
Überprüfung der Systemstabilität bei Nichterreichbarkeit des MySQL-Datenbankdienstes.

**Protokoll:**
- **Beobachtung:** Bei deaktiviertem MySQL-Dienst in der XAMPP-Umgebung kam es initial zu einem schwerwiegenden PHP-Fehler (Uncaught PDOException). Die Seite wurde nicht mehr gerendert, was zu einem "White Screen of Death" führte.
- **Feedback Julia an Angelo:** "Angelo, wenn die Datenbankverbindung unterbrochen wird, bricht das gesamte System zusammen. Das wirkt bei der Abgabe unprofessionell. Wir benötigen eine abgefangene Fehlermeldung."
- **Anweisung Vincent:** Vincent ordnete an, dass das System auch bei Verbindungsfehlern ein korrektes HTML-Grundgerüst ausgeben muss, ergänzt um einen dezenten Hinweis auf die Wartungsarbeiten an der Datenbank.

**Lösung (Angelo):**
Die PDO-Instanziierung in der `config.php` wurde in einen `try-catch`-Block eingebettet. Die Variable `$pdo` wird im Fehlerfall kontrolliert auf `null` gesetzt. Die Seiten `products.php` und `order.php` prüfen nun diesen Status und geben eine benutzerfreundliche Meldung aus, ohne den Skriptablauf zu unterbrechen.

---

### Testfall TF-002: Validierung von Datenbank-Einträgen (Fehlerhafte Werte)
- **Datum:** 03. Mai 2026
- **Prüfer:** Julia
- **Status:** BEHOBEN

**Beschreibung:**
Prüfung der Darstellung und Verarbeitung bei inkonsistenten oder fehlerhaften Werten in der Datenbank (z. B. fehlende Preise oder falsche Datentypen).

**Protokoll:**
- **Beobachtung:** Julia stellte fest, dass Produkte ohne Preisangabe (NULL-Werte) in der Tabelle `products` zu einer fehlerhaften Anzeige in der Produktliste führten (leeres Preisfeld). Zudem wurden negative Mengen im Bestellformular nicht serverseitig abgefangen.
- **Feedback Julia an Angelo:** "Angelo, ich habe Testdaten mit fehlenden Preisen angelegt und die Tabelle sieht dann kaputt aus. Außerdem kann ich '-5 Besen' bestellen, was keinen Sinn ergibt."
- **Anweisung Vincent:** Vincent verlangte eine strikte Validierung der Eingabewerte und eine Fallback-Lösung für die Preisanzeige.

**Lösung (Angelo):**
In der `products.php` wurde eine Formatierung via `number_format()` implementiert, die nun auch NULL-Werte abfängt. In der `order.php` wurde die Variable `$menge` durch einen expliziten Typecast `(int)` sowie eine `min="1"` Validierung im HTML-Formular abgesichert.

---

### Testfall TF-003: Dublettenprüfung bei der Registrierung
- **Datum:** 04. Mai 2026
- **Prüfer:** Julia
- **Status:** ERFOLGREICH

**Beschreibung:**
Sicherstellung, dass E-Mail-Adressen nicht mehrfach registriert werden können.

**Ergebnis:**
- Der Versuch, einen bestehenden Account erneut anzulegen, wird durch den `UNIQUE`-Constraint der Datenbank abgefangen.
- Angelo hat die Fehlermeldung in der `register.php` so angepasst, dass der Nutzer über die bereits existierende E-Mail informiert wird.

---

### Testfall TF-004: End-to-End Bestellprozess
- **Datum:** 04. Mai 2026
- **Prüfer:** Julia
- **Status:** ERFOLGREICH

**Beschreibung:**
Vollständiger Durchlauf einer Bestellung von der Artikelauswahl bis zur Speicherung in der Datenbank.

**Ergebnis:**
- Die Fremdschlüsselbeziehungen zwischen `customers` und `products` innerhalb der Tabelle `orders` funktionieren einwandfrei.
- Die visuelle Rückmeldung für den Kunden nach Abschluss der Bestellung wurde optimiert.

---

### Testfall TF-005: Checkout-Prozess mit Zahlungsmethode
- **Datum:** 05. Mai 2026
- **Prüfer:** Julia
- **Status:** ERFOLGREICH

**Beschreibung:**
Vollständiger Test des neuen dreistufigen Checkout-Prozesses mit Produktauswahl, Zahlungsmethode und Bestellzusammenfassung.

**Ergebnis:**
- Die Produktauswahl per Dropdown funktioniert korrekt, die Live-Zusammenfassung aktualisiert sich in Echtzeit.
- Alle vier Zahlungsmethoden (Rechnung, PayPal, Kreditkarte, Vorkasse) werden korrekt gespeichert.
- Der Lagerbestand wird nach Bestellung automatisch reduziert.
- Die Bestätigungsseite zeigt alle relevanten Daten (Lieferadresse, Kundendaten, Zahlungsmethode).

---

### Testfall TF-006: Bestellstornierung
- **Datum:** 05. Mai 2026
- **Prüfer:** Julia
- **Status:** ERFOLGREICH

**Beschreibung:**
Prüfung der Stornierungsfunktion in der Bestellübersicht. Eine bestehende Bestellung soll gelöscht und der Lagerbestand wiederhergestellt werden.

**Ergebnis:**
- Der "Stornieren"-Button zeigt einen Bestätigungsdialog vor dem Löschen.
- Nach Stornierung wird der Datensatz aus der `orders`-Tabelle gelöscht.
- Der Lagerbestand des betroffenen Produkts wird korrekt um die stornierte Menge erhöht.
- Die Stornierung ist nur für eigene Bestellungen des eingeloggten Kunden möglich.

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
