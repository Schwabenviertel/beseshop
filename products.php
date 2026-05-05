<?php 
// Bindet den Kopfbereich der Webseite ein (Navigation, HTML-Grundgerüst)
include 'header.php'; 

// Initialisiert ein leeres Array für die Produkte
$products = [];
// Prüft, ob eine aktive Datenbankverbindung ($pdo) besteht
if ($pdo) {
    // Führt eine SQL-Abfrage aus, um alle Einträge aus der Tabelle 'products' zu holen
    $stmt = $pdo->query("SELECT * FROM products");
    // Ruft alle Datensätze ab und speichert sie im Array $products
    $products = $stmt->fetchAll();
} else {
    // Gibt eine Fehlermeldung aus, falls keine Verbindung zur Datenbank möglich ist
    echo "<p style='color:red; text-align:center;'>Datenbankverbindung fehlgeschlagen! Bitte check die config.php oder importier die database.sql.</p>";
}
?>

<!-- Bereich für die Produktliste -->
<section class="container">
    <!-- Zeigt die übersetzte Überschrift "Alle Besen" an -->
    <h2 style="margin: 40px 0; text-align: center;"><?php echo t('nav_products'); ?></h2>
    
    <?php if (!empty($products)): ?>
    <!-- Falls Produkte vorhanden sind, wird eine Tabelle erzeugt -->
    <table>
        <thead> <!-- Kopfzeile der Tabelle -->
            <tr>
                <!-- Übersetzte Spaltenüberschriften -->
                <th><?php echo t('product_no'); ?></th>
                <th>Name</th>
                <th>Beschreibung</th>
                <th>Preis</th>
                <th>Aktion</th>
            </tr>
        </thead>
        <tbody> <!-- Tabellenkörper mit den Produktdaten -->
            <?php foreach ($products as $p): ?>
            <!-- Schleife durchläuft jedes gefundene Produkt im Array -->
            <tr>
                <!-- Zeigt die Artikelnummer fettgedruckt an -->
                <td><strong><?php echo $p['product_number']; ?></strong></td>
                <!-- Zeigt den Produktnamen an -->
                <td><?php echo $p['name']; ?></td>
                <!-- Zeigt die Produktbeschreibung an -->
                <td><?php echo $p['description']; ?></td>
                <!-- Formatiert den Preis auf 2 Dezimalstellen mit Komma als Trenner -->
                <td><?php echo number_format($p['price'], 2, ',', '.'); ?> €</td>
                <!-- Link zum Bestellen des jeweiligen Produkts, übergibt die Artikelnummer per URL -->
                <td><a href="order.php?product_no=<?php echo $p['product_number']; ?>" class="btn" style="padding: 5px 10px; font-size: 12px;"><?php echo t('nav_order'); ?></a></td>
            </tr>
            <?php endforeach; ?> <!-- Ende der Produktschleife -->
        </tbody>
    </table>
    <?php endif; ?>
</section>

<?php 
// Bindet den Fußbereich der Webseite ein
include 'footer.php'; 
?>
