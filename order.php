<?php
/**
 *                           _  __            _
 *  _ __ ___  _   _ ___  ___(_)/ _| __ _ _ __(_)
 * | '_ ` _ \| | | / __|/ __| | |_ / _` | '__| |
 * | | | | | | |_| \__ \ (__| |  _| (_| | |  | |
 * |_| |_| |_|\__,_|___/\___|_|_|  \__,_|_|  |_|
 *
 * Checkout-Seite des BESE.CO Webshops.
 * Unterstützt sowohl Einzelprodukt-Bestellung als auch Warenkorb-Checkout.
 * Der Kunde wählt eine Zahlungsmethode und bestätigt die Bestellung.
 * Nur eingeloggte Kunden haben Zugriff.
 */
include 'header.php';

// Zugriffsschutz: nur eingeloggte Kunden dürfen bestellen
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

// Warenkorb in der Session initialisieren, falls noch nicht vorhanden
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$msg   = "";
$error = "";
$products = [];
$checkout_items = [];
$checkout_total = 0;
$selected_product_id = $_GET['product_id'] ?? "";

// Bestellung verarbeiten: Zahlungsmethode prüfen und Bestellung in die DB schreiben
if ($_SERVER["REQUEST_METHOD"] == "POST" && $pdo) {
    $zahlungsart = $_POST['zahlungsart'] ?? '';
    $customer_id = $_SESSION['customer_id'];

    if (empty($zahlungsart)) {
        $error = "Bitte a Zahlungsmethode auswähla.";
    } else {
        $order_ids = [];

        try {
            // Transaktion starten für atomare Bestellverarbeitung
            $pdo->beginTransaction();

            // Warenkorb-Checkout: alle Artikel aus dem Warenkorb bestellen
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $pid => $qty) {
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND stock >= ?");
                    $stmt->execute([$pid, $qty]);
                    $prod = $stmt->fetch();

                    if ($prod) {
                        $stmt = $pdo->prepare("INSERT INTO orders (customer_id, product_id, quantity, payment_method) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$customer_id, $pid, $qty, $zahlungsart]);
                        $order_ids[] = $pdo->lastInsertId();
                        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                        $stmt->execute([$qty, $pid]);
                    } else {
                        $pdo->rollBack();
                        $error = "Oi oder mehrere Produkd send nemme en ausreichender Meng do.";
                        break;
                    }
                }

                if (!$error) {
                    $pdo->commit();
                    $_SESSION['cart'] = [];
                    $_SESSION['last_order_ids'] = $order_ids;
                    header("Location: order_confirmation.php");
                    exit;
                }
            // Einzelprodukt-Bestellung: direkter Kauf ohne Warenkorb
            } elseif (isset($_POST['product_id'])) {
                $product_id = (int)$_POST['product_id'];
                $menge = (int)$_POST['menge'];

                if ($product_id <= 0 || $menge <= 0) {
                    $pdo->rollBack();
                    $error = "Bitte Produkd ond Meng auswähla.";
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND stock >= ?");
                    $stmt->execute([$product_id, $menge]);
                    $prod = $stmt->fetch();

                    if ($prod) {
                        $stmt = $pdo->prepare("INSERT INTO orders (customer_id, product_id, quantity, payment_method) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$customer_id, $product_id, $menge, $zahlungsart]);
                        $order_ids[] = $pdo->lastInsertId();
                        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                        $stmt->execute([$menge, $product_id]);
                        $pdo->commit();
                        $_SESSION['last_order_ids'] = $order_ids;
                        header("Location: order_confirmation.php");
                        exit;
                    } else {
                        $pdo->rollBack();
                        $error = "Produkd ned verfügbar oder ned gnug uf Lager.";
                    }
                }
            } else {
                $pdo->rollBack();
                $error = "Koine Ardikel zum Bschdella do.";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Fehler bei dr Bschdelllung. Bitte nomal brobiara.";
        }
    }
}

// Verfügbare Produkte für die Einzelprodukt-Auswahl laden
if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM products WHERE stock > 0 ORDER BY name");
    $products = $stmt->fetchAll();
}

$has_cart = !empty($_SESSION['cart']);

// Warenkorb-Artikel für die Checkout-Übersicht laden und Gesamtpreis berechnen
if ($has_cart && $pdo) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $cart_products = $stmt->fetchAll();

    foreach ($cart_products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        if ($qty > $p['stock']) { $qty = $p['stock']; $_SESSION['cart'][$p['id']] = $qty; }
        if ($qty > 0) {
            $subtotal = $p['price'] * $qty;
            $checkout_total += $subtotal;
            $checkout_items[] = ['id' => $p['id'], 'name' => $p['name'], 'product_number' => $p['product_number'], 'price' => $p['price'], 'stock' => $p['stock'], 'quantity' => $qty, 'subtotal' => $subtotal];
        }
    }
}

// Vorausgewähltes Produkt laden (wenn per URL-Parameter übergeben)
$selected_product = null;
if (!$has_cart && $selected_product_id && $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$selected_product_id]);
    $selected_product = $stmt->fetch();
}
?>

<section class="container">
    <div class="checkout-wrapper">
        <h2 class="checkout-title">Kass</h2>
        <p class="checkout-subtitle">Eigloggd als <strong><?php echo htmlspecialchars($_SESSION['customer_name']); ?></strong> (<?php echo htmlspecialchars($_SESSION['customer_number']); ?>)</p>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="order.php" method="POST" class="checkout-form">

            <?php if ($has_cart && !empty($checkout_items)): ?>
                <div class="checkout-section">
                    <h3 class="section-heading">1. Deine Ardikel</h3>
                    <div class="checkout-items-list">
                        <?php foreach ($checkout_items as $item): ?>
                            <div class="checkout-item-row">
                                <span class="checkout-item-name"><?php echo htmlspecialchars($item['name']); ?> <small>(<?php echo htmlspecialchars($item['product_number']); ?>)</small></span>
                                <span class="checkout-item-qty"><?php echo $item['quantity']; ?>x</span>
                                <span class="checkout-item-price"><?php echo number_format($item['subtotal'], 2, ',', '.'); ?> &euro;</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p style="text-align: right; margin-top: 10px;"><a href="cart.php" style="font-size: 13px; color: var(--muted);">Warakorb bearbeida</a></p>
                </div>
            <?php else: ?>
                <div class="checkout-section">
                    <h3 class="section-heading">1. Produkd auswähla</h3>
                    <div class="form-group">
                        <label>Produkd</label>
                        <select name="product_id" id="product-select" required>
                            <option value="">-- Produkd wähla --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?php echo $p['id']; ?>"
                                    data-price="<?php echo $p['price']; ?>"
                                    data-stock="<?php echo $p['stock']; ?>"
                                    data-name="<?php echo htmlspecialchars($p['name']); ?>"
                                    <?php echo ($selected_product && $p['id'] == $selected_product['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p['name']); ?> (<?php echo $p['product_number']; ?>) - <?php echo number_format($p['price'], 2, ',', '.'); ?> &euro; (<?php echo $p['stock']; ?> verfügbar)

                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Meng</label>
                        <input type="number" name="menge" id="menge-input" value="1" min="1" max="<?php echo $selected_product ? $selected_product['stock'] : 99; ?>" required>
                        <p id="stock-warning" class="stock-warning" style="display: none;"></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="checkout-section">
                <h3 class="section-heading">2. Zahlungsmethode</h3>

                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="zahlungsart" value="Rechnung" required>
                        <div class="payment-card">
                            <span class="payment-icon">&#128196;</span>
                            <span class="payment-label">Rechnung</span>
                            <span class="payment-desc">Zahlung innerhalb 14 Dag</span>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="zahlungsart" value="PayPal">
                        <div class="payment-card">
                            <span class="payment-icon">&#128179;</span>
                            <span class="payment-label">PayPal</span>
                            <span class="payment-desc">Sofordige Bezahlung</span>
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
                            <span class="payment-desc">Überweisung vorab
</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="checkout-section">
                <h3 class="section-heading">3. Zammafassung</h3>
                <div class="order-summary">
                    <?php if ($has_cart && !empty($checkout_items)): ?>
                        <?php foreach ($checkout_items as $item): ?>
                            <div class="summary-row">
                                <span><?php echo htmlspecialchars($item['name']); ?> (<?php echo $item['quantity']; ?>x)</span>
                                <span><?php echo number_format($item['subtotal'], 2, ',', '.'); ?> &euro;</span>
                            </div>
                        <?php endforeach; ?>
                        <div class="summary-row summary-total">
                            <span>Gsamd</span>
                            <span><?php echo number_format($checkout_total, 2, ',', '.'); ?> &euro;</span>
                        </div>
                    <?php else: ?>
                        <div class="summary-row">
                            <span>Produkd</span>
                            <span id="summary-product">-</span>
                        </div>
                        <div class="summary-row">
                            <span>Meng</span>
                            <span id="summary-qty">1</span>
                        </div>
                        <div class="summary-row">
                            <span>Einzelpreis</span>

                            <span id="summary-price">-</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Gsamd</span>
                            <span id="summary-total">-</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn-submit btn-checkout">Jetzd bschdella</button>
        </form>
    </div>
</section>

<?php if (!$has_cart): ?>
<script>
(function() {
    var select = document.getElementById('product-select');
    var mengeInput = document.getElementById('menge-input');
    if (!select || !mengeInput) return;
    var summaryProduct = document.getElementById('summary-product');
    var summaryQty = document.getElementById('summary-qty');
    var summaryPrice = document.getElementById('summary-price');
    var summaryTotal = document.getElementById('summary-total');
    var stockWarning = document.getElementById('stock-warning');

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

            if (menge >= stock) {
                stockWarning.textContent = 'Du hosch die maximale Aazahl erreicht (' + stock + ' Stk. do)';
                stockWarning.style.display = 'block';
            } else if (stock <= 5) {
                stockWarning.textContent = 'Bloß no ' + stock + ' Stk. do!';
                stockWarning.style.display = 'block';
            } else {
                stockWarning.style.display = 'none';
            }
        } else {
            summaryProduct.textContent = '-';
            summaryQty.textContent = menge;
            summaryPrice.textContent = '-';
            summaryTotal.textContent = '-';
            stockWarning.style.display = 'none';
        }
    }

    select.addEventListener('change', update);
    mengeInput.addEventListener('input', update);
    update();
})();
</script>
<?php endif; ?>

<?php include 'footer.php'; ?>
