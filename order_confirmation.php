<?php
/**
 * Bestellbestaetigungsseite des BESE.CO Webshops.
 * Wird nach erfolgreichem Checkout angezeigt und zeigt alle Details:
 * Bestellnummer, Produkt, Menge, Preis, Lieferadresse und Zahlungsmethode.
 * Nur der jeweilige Kunde kann seine eigene Bestellung einsehen.
 */
include 'header.php';

// Nur eingeloggte Kunden duerfen diese Seite sehen
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$order = null;
$order_id = (int)($_GET['id'] ?? 0);
$zahlungsart = $_SESSION['last_payment'] ?? 'Nicht angegeben';

// Bestelldaten inkl. Produkt- und Kundendaten laden
if ($order_id > 0 && $pdo) {
    $stmt = $pdo->prepare("
        SELECT o.id, o.quantity, o.order_date,
               p.name AS product_name, p.product_number, p.price,
               c.first_name, c.last_name, c.email, c.customer_number,
               c.street, c.zip_code, c.city
        FROM orders o
        JOIN products p ON o.product_id = p.id
        JOIN customers c ON o.customer_id = c.id
        WHERE o.id = ? AND o.customer_id = ?
    ");
    $stmt->execute([$order_id, $_SESSION['customer_id']]);
    $order = $stmt->fetch();
}

// Falls keine gueltige Bestellung gefunden, zurueck zum Checkout
if (!$order) {
    header("Location: order.php");
    exit;
}

$total = $order['price'] * $order['quantity'];
?>

<section class="container">
    <div class="confirmation-wrapper">
        <div class="confirmation-header">
            <span class="confirmation-icon">&#10003;</span>
            <h2>Bestellung erfolgreich!</h2>
            <p>Vielen Dank fuer Ihre Bestellung, <?php echo htmlspecialchars($order['first_name']); ?>.</p>
        </div>

        <div class="confirmation-grid">
            <div class="confirmation-card">
                <h3>Bestelldetails</h3>
                <div class="detail-row">
                    <span>Bestellnummer</span>
                    <strong>#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></strong>
                </div>
                <div class="detail-row">
                    <span>Datum</span>
                    <span><?php echo date('d.m.Y, H:i', strtotime($order['order_date'])); ?> Uhr</span>
                </div>
                <div class="detail-row">
                    <span>Produkt</span>
                    <span><?php echo htmlspecialchars($order['product_name']); ?> (<?php echo htmlspecialchars($order['product_number']); ?>)</span>
                </div>
                <div class="detail-row">
                    <span>Menge</span>
                    <span><?php echo $order['quantity']; ?></span>
                </div>
                <div class="detail-row">
                    <span>Einzelpreis</span>
                    <span><?php echo number_format($order['price'], 2, ',', '.'); ?> &euro;</span>
                </div>
                <div class="detail-row detail-total">
                    <span>Gesamtbetrag</span>
                    <strong><?php echo number_format($total, 2, ',', '.'); ?> &euro;</strong>
                </div>
            </div>

            <div class="confirmation-card">
                <h3>Lieferadresse</h3>
                <div class="address-block">
                    <p><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                    <p><?php echo htmlspecialchars($order['street'] ?: 'Keine Strasse angegeben'); ?></p>
                    <p><?php echo htmlspecialchars(($order['zip_code'] ?: '') . ' ' . ($order['city'] ?: 'Keine Stadt angegeben')); ?></p>
                </div>

                <h3 style="margin-top: 25px;">Kundendaten</h3>
                <div class="detail-row">
                    <span>Kundennummer</span>
                    <span><?php echo htmlspecialchars($order['customer_number']); ?></span>
                </div>
                <div class="detail-row">
                    <span>E-Mail</span>
                    <span><?php echo htmlspecialchars($order['email']); ?></span>
                </div>

                <h3 style="margin-top: 25px;">Zahlungsmethode</h3>
                <div class="detail-row">
                    <span>Bezahlung</span>
                    <span><?php echo htmlspecialchars($zahlungsart); ?></span>
                </div>
            </div>
        </div>

        <div class="confirmation-actions">
            <a href="products.php" class="btn">Weiter einkaufen</a>
            <a href="my_orders.php" class="btn btn-secondary">Meine Bestellungen</a>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
