<?php 
// Bindet den Kopfbereich der Webseite ein (Navigation, HTML-Grundgerüst)
include 'header.php'; 

// Initialisiert Variablen für Erfolgs- und Fehlermeldungen
$msg = "";
$error = "";
// Holt die Artikelnummer aus der URL (falls vorhanden), um das Feld vorauszufüllen
$p_pre = $_GET['product_no'] ?? "";

// Prüft, ob das Formular per POST abgeschickt wurde und ob die DB-Verbindung steht
if ($_SERVER["REQUEST_METHOD"] == "POST" && $pdo) {
    // Speichert die eingegebenen Daten (Kundennummer, Artikelnummer, Menge)
    $c_nr = $_POST['c_nr'];
    $p_nr = $_POST['p_nr'];
    $menge = (int)$_POST['menge']; // Wandelt die Menge in eine Ganzzahl um
    
    // Sucht die interne ID des Kunden basierend auf der Kundennummer
    $stmt = $pdo->prepare("SELECT id FROM customers WHERE customer_number = ?");
    $stmt->execute([$c_nr]);
    $kunde = $stmt->fetch(); // Holt das Ergebnis der Abfrage
    
    // Sucht die interne ID des Produkts basierend auf der Artikelnummer
    $stmt = $pdo->prepare("SELECT id FROM products WHERE product_number = ?");
    $stmt->execute([$p_nr]);
    $prod = $stmt->fetch(); // Holt das Ergebnis der Abfrage
    
    // Prüft, ob sowohl Kunde als auch Produkt in der Datenbank existieren
    if ($kunde && $prod) {
        try {
            // SQL zum Einfügen der Bestellung in die 'orders'-Tabelle
            $stmt = $pdo->prepare("INSERT INTO orders (customer_id, product_id, quantity) VALUES (?, ?, ?)");
            // Verknüpft die IDs und die Menge und führt den Befehl aus
            $stmt->execute([$kunde['id'], $prod['id'], $menge]);
            // Setzt eine Erfolgsmeldung
            $msg = "Bestellung erfolgreich abgeschickt!";
        } catch (PDOException $e) {
            // Falls beim Einfügen ein Datenbankfehler auftritt
            $error = "Fehler: " . $e->getMessage();
        }
    } else {
        // Falls Kundennummer oder Artikelnummer falsch eingegeben wurden
        $error = "Kunde oder Produkt nicht gefunden!";
    }
}
?>

<!-- Bereich für das Bestellformular -->
<section class="container">
    <!-- Zeigt die übersetzte Überschrift "Bestell-Formular" an -->
    <h2 style="text-align: center; margin-top: 40px;"><?php echo t('order_form'); ?></h2>
    
    <?php if ($msg): ?>
        <!-- Zeigt eine grüne Erfolgsmeldung an -->
        <div style="background: #dff0d8; padding: 20px; margin: 20px auto; max-width: 500px; text-align: center;">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <!-- Zeigt eine rote Fehlermeldung an -->
        <div style="background: #f2dede; padding: 20px; margin: 20px auto; max-width: 500px; text-align: center;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Das Formular schickt die Daten an order.php per POST -->
    <form action="order.php" method="POST">
        <div class="form-group"> <!-- Gruppe für Kundennummer -->
            <label><?php echo t('customer_no'); ?></label>
            <input type="text" name="c_nr" required> <!-- Pflichtfeld -->
        </div>
        <div class="form-group"> <!-- Gruppe für Artikelnummer -->
            <label><?php echo t('product_no'); ?></label>
            <!-- Füllt das Feld mit dem Wert aus der URL (falls vorhanden) voraus -->
            <input type="text" name="p_nr" value="<?php echo $p_pre; ?>" required>
        </div>
        <div class="form-group"> <!-- Gruppe für Menge -->
            <label>Menge</label>
            <!-- Standardwert 1, mindestens 1 muss bestellt werden -->
            <input type="number" name="menge" value="1" min="1" required>
        </div>
        <!-- Absende-Button mit übersetztem Text -->
        <button type="submit" class="btn-submit"><?php echo t('submit'); ?></button>
    </form>
</section>

<?php 
// Bindet den Fußbereich der Webseite ein
include 'footer.php'; 
?>
