<?php
/**
 * Checkout-Seite des BESE.CO Webshops.
 * Der Kunde waehlt ein Produkt, die Menge und eine Zahlungsmethode.
 * Nach erfolgreicher Bestellung wird zur Bestaetigungsseite weitergeleitet.
 * Nur eingeloggte Kunden haben Zugriff.
 */
include 'header.php';

// Nur eingeloggte Kunden duerfen bestellen
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$msg   = "";
$error = "";
$products = [];
$selected_product_id = $_GET['product_id'] ?? "";

// Alle verfuegbaren Produkte laden (nur mit Lagerbestand)
if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM products WHERE stock > 0 ORDER BY name");
    $products = $stmt->fetchAll();
}

// Bestellung verarbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST" && $pdo) {
    $product_id  = (int)$_POST['product_id'];
    $menge       = (int)$_POST['menge'];
    $zahlungsart = $_POST['zahlungsart'] ?? '';
    $customer_id = $_SESSION['customer_id'];

    // Eingaben validieren
    if ($product_id <= 0 || $menge <= 0) {
        $error = "Bitte Produkt und Menge auswaehlen.";
    } elseif (empty($zahlungsart)) {
        $error = "Bitte eine Zahlungsmethode auswaehlen.";
    } else {
        // Pruefen ob Produkt existiert und genug auf Lager ist
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND stock >= ?");
        $stmt->execute([$product_id, $menge]);
        $prod = $stmt->fetch();

        if ($prod) {
            try {
                // Transaktion: Bestellung speichern und Lagerbestand reduzieren
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("INSERT INTO orders (customer_id, product_id, quantity) VALUES (?, ?, ?)");
                $stmt->execute([$customer_id, $product_id, $menge]);
                $order_id = $pdo->lastInsertId();
                $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmt->execute([$menge, $product_id]);
                $pdo->commit();

                // Zahlungsart in Session speichern und zur Bestaetigung weiterleiten
                $_SESSION['last_payment'] = $zahlungsart;
                header("Location: order_confirmation.php?id=" . $order_id);
                exit;
            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = "Fehler bei der Bestellung. Bitte erneut versuchen.";
            }
        } else {
            $error = "Produkt nicht verfuegbar oder nicht genug auf Lager.";
        }
    }
}

// Falls ein Produkt per URL vorausgewaehlt wurde
$selected_product = null;
if ($selected_product_id && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$selected_product_id]);
    $selected_product = $stmt->fetch();
}
?>

<section class="container">
    <div class="checkout-wrapper">
        <h2 class="checkout-title">Checkout</h2>
        <p class="checkout-subtitle">Eingeloggt als <strong><?php echo htmlspecialchars($_SESSION['customer_name']); ?></strong> (<?php echo htmlspecialchars($_SESSION['customer_number']); ?>)</p>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="order.php" method="POST" class="checkout-form">
            <div class="checkout-section">
                <h3 class="section-heading">1. Produkt auswaehlen</h3>
                <div class="form-group">
                    <label>Produkt</label>
                    <select name="product_id" id="product-select" required>
                        <option value="">-- Produkt waehlen --</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?php echo $p['id']; ?>"
                                data-price="<?php echo $p['price']; ?>"
                                data-stock="<?php echo $p['stock']; ?>"
                                data-name="<?php echo htmlspecialchars($p['name']); ?>"
                                <?php echo ($selected_product && $p['id'] == $selected_product['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['name']); ?> (<?php echo $p['product_number']; ?>) - <?php echo number_format($p['price'], 2, ',', '.'); ?> &euro; (<?php echo $p['stock']; ?> verfuegbar)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Menge</label>
                    <input type="number" name="menge" id="menge-input" value="1" min="1" max="<?php echo $selected_product ? $selected_product['stock'] : 99; ?>" required>
                </div>
            </div>

            <div class="checkout-section">
                <h3 class="section-heading">2. Zahlungsmethode</h3>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="zahlungsart" value="Rechnung" required>
                        <div class="payment-card">
                            <span class="payment-icon">&#128196;</span>
                            <span class="payment-label">Rechnung</span>
                            <span class="payment-desc">Zahlung innerhalb 14 Tage</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="zahlungsart" value="PayPal">
                        <div class="payment-card">
                            <span class="payment-icon">&#128179;</span>
                            <span class="payment-label">PayPal</span>
                            <span class="payment-desc">Sofortige Bezahlung</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="zahlungsart" value="Kreditkarte">
                        <div class="payment-card">
                            <span class="payment-icon">&#128179;</span>
                            <span class="payment-label">Kreditkarte</span>
                            <span class="payment-desc">Visa / Mastercard</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="zahlungsart" value="Vorkasse">
                        <div class="payment-card">
                            <span class="payment-icon">&#127974;</span>
                            <span class="payment-label">Vorkasse</span>
                            <span class="payment-desc">Ueberweisung vorab</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="checkout-section">
                <h3 class="section-heading">3. Zusammenfassung</h3>
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Produkt</span>
                        <span id="summary-product">-</span>
                    </div>
                    <div class="summary-row">
                        <span>Menge</span>
                        <span id="summary-qty">1</span>
                    </div>
                    <div class="summary-row">
                        <span>Einzelpreis</span>
                        <span id="summary-price">-</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Gesamt</span>
                        <span id="summary-total">-</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit btn-checkout">Jetzt bestellen</button>
        </form>
    </div>
</section>

<script>
(function() {
    var select = document.getElementById('product-select');
    var mengeInput = document.getElementById('menge-input');
    var summaryProduct = document.getElementById('summary-product');
    var summaryQty = document.getElementById('summary-qty');
    var summaryPrice = document.getElementById('summary-price');
    var summaryTotal = document.getElementById('summary-total');

    function update() {
        var opt = select.options[select.selectedIndex];
        var menge = parseInt(mengeInput.value) || 1;
        if (opt && opt.value) {
            var price = parseFloat(opt.dataset.price);
            var stock = parseInt(opt.dataset.stock);
            var name = opt.dataset.name;
            mengeInput.max = stock;
            if (menge > stock) { menge = stock; mengeInput.value = stock; }
            summaryProduct.textContent = name;
            summaryQty.textContent = menge;
            summaryPrice.textContent = price.toFixed(2).replace('.', ',') + ' \u20AC';
            summaryTotal.textContent = (price * menge).toFixed(2).replace('.', ',') + ' \u20AC';
        } else {
            summaryProduct.textContent = '-';
            summaryQty.textContent = menge;
            summaryPrice.textContent = '-';
            summaryTotal.textContent = '-';
        }
    }

    select.addEventListener('change', update);
    mengeInput.addEventListener('input', update);
    update();
})();
</script>

<?php include 'footer.php'; ?>
