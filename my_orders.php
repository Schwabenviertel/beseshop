<?php
/**
 * Bestelluebersicht des eingeloggten Kunden.
 * Zeigt alle bisherigen Bestellungen an und ermoeglicht die Stornierung.
 */
include 'header.php';

// Nur eingeloggte Kunden duerfen diese Seite sehen
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
$error = "";

// Stornierung verarbeiten (POST-Request mit Bestell-ID)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_order_id']) && $pdo) {
    $cancel_id = (int)$_POST['cancel_order_id'];
    $customer_id = $_SESSION['customer_id'];

    // Bestellung laden und pruefen, ob sie dem Kunden gehoert
    $stmt = $pdo->prepare("SELECT o.id, o.quantity, o.product_id FROM orders o WHERE o.id = ? AND o.customer_id = ?");
    $stmt->execute([$cancel_id, $customer_id]);
    $cancel_order = $stmt->fetch();

    if ($cancel_order) {
        try {
            // Transaktion: Bestellung loeschen und Lagerbestand wiederherstellen
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->execute([$cancel_id]);
            $stmt = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $stmt->execute([$cancel_order['quantity'], $cancel_order['product_id']]);
            $pdo->commit();
            $msg = "Bestellung #" . str_pad($cancel_id, 5, '0', STR_PAD_LEFT) . " wurde erfolgreich storniert.";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Fehler beim Stornieren. Bitte erneut versuchen.";
        }
    } else {
        $error = "Bestellung nicht gefunden.";
    }
}

// Alle Bestellungen des Kunden laden (neueste zuerst)
$orders = [];
if ($pdo) {
    $stmt = $pdo->prepare("
        SELECT o.id, o.quantity, o.order_date,
               p.name AS product_name, p.product_number, p.price
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.customer_id = ?
        ORDER BY o.order_date DESC
    ");
    $stmt->execute([$_SESSION['customer_id']]);
    $orders = $stmt->fetchAll();
}
?>

<section class="container">
    <div class="orders-wrapper">
        <h2 class="orders-title">Meine Bestellungen</h2>
        <p class="orders-subtitle"><?php echo htmlspecialchars($_SESSION['customer_name']); ?> (<?php echo htmlspecialchars($_SESSION['customer_number']); ?>)</p>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (empty($orders)): ?>
            <div class="orders-empty">
                <p>Sie haben noch keine Bestellungen aufgegeben.</p>
                <a href="products.php" class="btn" style="margin-top: 20px;">Jetzt einkaufen</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $o): ?>
                    <div class="order-item">
                        <div class="order-item-header">
                            <span class="order-number">Bestellung #<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?></span>
                            <span class="order-date"><?php echo date('d.m.Y, H:i', strtotime($o['order_date'])); ?> Uhr</span>
                        </div>
                        <div class="order-item-body">
                            <div class="order-product">
                                <strong><?php echo htmlspecialchars($o['product_name']); ?></strong>
                                <span class="order-product-nr"><?php echo htmlspecialchars($o['product_number']); ?></span>
                            </div>
                            <div class="order-details">
                                <span>Menge: <?php echo $o['quantity']; ?></span>
                                <span><?php echo number_format($o['price'], 2, ',', '.'); ?> &euro;/Stk.</span>
                                <strong><?php echo number_format($o['price'] * $o['quantity'], 2, ',', '.'); ?> &euro;</strong>
                                <form action="my_orders.php" method="POST" class="cancel-form" onsubmit="return confirm('Bestellung #<?php echo str_pad($o['id'], 5, '0', STR_PAD_LEFT); ?> wirklich stornieren?');">
                                    <input type="hidden" name="cancel_order_id" value="<?php echo $o['id']; ?>">
                                    <button type="submit" class="btn-cancel">Stornieren</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <p class="orders-count"><?php echo count($orders); ?> Bestellung<?php echo count($orders) !== 1 ? 'en' : ''; ?> insgesamt</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
