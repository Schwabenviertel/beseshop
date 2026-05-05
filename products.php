<?php
/**
 * Produktuebersichtsseite des BESE.CO Webshops.
 * Laedt alle Produkte aus der Datenbank und zeigt sie tabellarisch an.
 * Ueber den "Bestellen"-Button gelangt der Kunde direkt zum Checkout.
 */
include 'header.php';

$products = [];

// Alle Produkte aus der Datenbank laden
if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
} else {
    echo "<p style='color:red; text-align:center;'>Datenbankverbindung fehlgeschlagen! Bitte database.sql in phpMyAdmin importieren.</p>";
}
?>

<section class="container">
    <h2 style="margin: 40px 0; text-align: center;">Unsere Produkte</h2>

    <?php if (!empty($products)): ?>
    <table>
        <thead>
            <tr>
                <th>Bild</th>
                <th>Artikelnummer</th>
                <th>Name</th>
                <th>Beschreibung</th>
                <th>Preis</th>
                <th>Aktion</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td style="font-size: 30px; text-align: center;">&#129529;</td>
                <td><strong><?php echo htmlspecialchars($p['product_number']); ?></strong></td>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['description']); ?></td>
                <td><?php echo number_format($p['price'], 2, ',', '.'); ?> &euro;</td>
                <td>
                    <a href="order.php?product_id=<?php echo $p['id']; ?>" class="btn" style="padding: 5px 10px; font-size: 12px;">
                        Bestellen
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</section>

<?php include 'footer.php'; ?>
