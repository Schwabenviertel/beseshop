<?php
/**
 * Produktübersichtsseite des BESE.CO Webshops.
 * Lädt alle Produkte aus der Datenbank und zeigt sie tabellarisch an.
 * Über den "In den Warenkorb"-Button kann der Kunde Produkte zum Warenkorb hinzufügen.
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
                <th>Verfügbar</th>
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
                    <?php if ($p['stock'] <= 0): ?>
                        <span style="color: #c0392b; font-weight: bold;">Ausverkauft</span>
                    <?php elseif ($p['stock'] <= 5): ?>
                        <span style="color: #c0392b; font-weight: bold;"><?php echo $p['stock']; ?> Stk.</span>
                    <?php else: ?>
                        <?php echo $p['stock']; ?> Stk.
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (isset($_SESSION['customer_id'])): ?>
                        <form action="cart.php" method="POST" style="display:inline; max-width:none; margin:0; padding:0; background:none;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                            <input type="hidden" name="menge" value="1">
                            <button type="submit" class="btn btn-cart-add">In den Warenkorb</button>
                        </form>
                    <?php else: ?>
                        <a href="login.php" class="btn" style="padding: 5px 10px; font-size: 12px;">Einloggen</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</section>

<?php include 'footer.php'; ?>
