<?php
/**
 *                           _  __            _
 *  _ __ ___  _   _ ___  ___(_)/ _| __ _ _ __(_)
 * | '_ ` _ \| | | / __|/ __| | |_ / _` | '__| |
 * | | | | | | |_| \__ \ (__| |  _| (_| | |  | |
 * |_| |_| |_|\__,_|___/\___|_|_|  \__,_|_|  |_|
 *
 * Bestellbestätigungsseite des BESE.CO Webshops.
 * Wird nach erfolgreichem Checkout angezeigt und zeigt alle Details:
 * Bestellnummer(n), Produkte, Mengen, Preise, Lieferadresse und Zahlungsmethode.
 * Unterstützt sowohl Einzel- als auch Mehrfachbestellungen (Warenkorb).
 */
include 'header.php';

// Zugriffsschutz: nur eingeloggte Kunden dürfen die Bestätigung sehen
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$orders = [];
$customer = null;
$total = 0;

// Bestell-IDs ermitteln: entweder aus URL-Parameter oder aus der Session
$order_id = (int)($_GET['id'] ?? 0);
$order_ids = $_SESSION['last_order_ids'] ?? [];

if ($order_id > 0) {
    $order_ids = [$order_id];
}

// Bestelldetails inkl. Produkt- und Kundendaten aus der DB laden
if (!empty($order_ids) && $pdo) {
    $placeholders = implode(',', array_fill(0, count($order_ids), '?'));
    $stmt = $pdo->prepare("
        SELECT o.id, o.quantity, o.order_date, o.payment_method,
               p.name AS product_name, p.product_number, p.price,
               c.first_name, c.last_name, c.email, c.customer_number,
               c.street, c.zip_code, c.city
        FROM orders o
        JOIN products p ON o.product_id = p.id
        JOIN customers c ON o.customer_id = c.id
        WHERE o.id IN ($placeholders) AND o.customer_id = ?
        ORDER BY o.id
    ");
    $params = array_merge($order_ids, [$_SESSION['customer_id']]);
    $stmt->execute($params);
    $orders = $stmt->fetchAll();

    // Kundendaten aus erster Bestellung übernehmen und Gesamtpreis berechnen
    if (!empty($orders)) {
        $customer = $orders[0];
        foreach ($orders as $o) {
            $total += $o['price'] * $o['quantity'];
        }
    }

    // Session-Daten der letzten Bestellung bereinigen
    unset($_SESSION['last_order_ids']);
}

// Falls keine Bestellungen gefunden: zurück zur Bestellseite
if (empty($orders)) {
    header("Location: order.php");
    exit;
}
?>

<section class="container">
    <div class="confirmation-wrapper">
        <div class="confirmation-header">
            <span class="confirmation-icon">&#10003;</span>
            <h2>Bschdelllung isch durch!</h2>
            <p>Dangschee fir dei Bschdelllung, <?php echo htmlspecialchars($customer['first_name']); ?>.</p>
        </div>

        <div class="confirmation-grid">
            <div class="confirmation-card">
                <h3>Bschdelldetails</h3>
                <?php foreach ($orders as $i => $order): ?>
                    <?php if (count($orders) > 1 && $i > 0): ?>
                        <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    <?php endif; ?>
                    <div class="detail-row">
                        <span>Bschdellnommer</span>
                        <strong>#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></strong>
                    </div>
                    <div class="detail-row">
                        <span>Dadum</span>
                        <span><?php echo date('d.m.Y, H:i', strtotime($order['order_date'])); ?> Uhr</span>
                    </div>
                    <div class="detail-row">
                        <span>Produkd</span>
                        <span><?php echo htmlspecialchars($order['product_name']); ?> (<?php echo htmlspecialchars($order['product_number']); ?>)</span>
                    </div>
                    <div class="detail-row">
                        <span>Meng</span>
                        <span><?php echo $order['quantity']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span>Einzelpreis</span>
                        <span><?php echo number_format($order['price'], 2, ',', '.'); ?> &euro;</span>
                    </div>
                    <div class="detail-row">
                        <span>Zwischasumm</span>
                        <strong><?php echo number_format($order['price'] * $order['quantity'], 2, ',', '.'); ?> &euro;</strong>
                    </div>
                <?php endforeach; ?>
                <div class="detail-row detail-total">
                    <span>Gsamdbedrag</span>
                    <strong><?php echo number_format($total, 2, ',', '.'); ?> &euro;</strong>
                </div>
            </div>

            <div class="confirmation-card">
                <h3>Lieferadress</h3>
                <div class="address-block">
                    <p><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></p>
                    <p><?php echo htmlspecialchars($customer['street'] ?: 'Koi Schdroß aageba'); ?></p>
                    <p><?php echo htmlspecialchars(($customer['zip_code'] ?: '') . ' ' . ($customer['city'] ?: 'Koi Schdadt aageba')); ?></p>
                </div>

                <h3 style="margin-top: 25px;">Kundadada</h3>
                <div class="detail-row">
                    <span>Kundanommer</span>
                    <span><?php echo htmlspecialchars($customer['customer_number']); ?></span>
                </div>
                <div class="detail-row">
                    <span>E-Mail</span>
                    <span><?php echo htmlspecialchars($customer['email']); ?></span>
                </div>

                <h3 style="margin-top: 25px;">Zahlungsmethode</h3>
                <div class="detail-row">
                    <span>Bezahlung</span>

                    <span><?php echo htmlspecialchars($customer['payment_method']); ?></span>
                </div>
            </div>
        </div>

        <div class="confirmation-actions">
            <a href="products.php" class="btn">Weider eikaufa</a>
            <a href="my_orders.php" class="btn btn-secondary">Mei Bschdellonga</a>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
