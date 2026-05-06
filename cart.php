<?php
/**
 * Warenkorb-Seite des BESE.CO Webshops.
 * Verwaltet den Session-basierten Warenkorb: Artikel hinzufügen, Menge ändern, entfernen.
 * Nur eingeloggte Kunden haben Zugriff.
 */
include 'header.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && $pdo) {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $product_id = (int)($_POST['product_id'] ?? 0);
        $menge = (int)($_POST['menge'] ?? 1);
        if ($menge < 1) $menge = 1;

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $prod = $stmt->fetch();

        if ($prod) {
            $current_qty = $_SESSION['cart'][$product_id] ?? 0;
            $new_qty = $current_qty + $menge;
            if ($new_qty > $prod['stock']) {
                $new_qty = $prod['stock'];
                $error = "Nur noch " . $prod['stock'] . " Stk. von \"" . $prod['name'] . "\" verfügbar.";
            }
            if ($new_qty > 0) {
                $_SESSION['cart'][$product_id] = $new_qty;
                if (!$error) {
                    $msg = htmlspecialchars($prod['name']) . " wurde zum Warenkorb hinzugefügt.";
                }
            }
        }
    } elseif ($action === 'update') {
        $product_id = (int)($_POST['product_id'] ?? 0);
        $menge = (int)($_POST['menge'] ?? 0);

        if ($menge <= 0) {
            unset($_SESSION['cart'][$product_id]);
            $msg = "Artikel wurde entfernt.";
        } else {
            $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $prod = $stmt->fetch();
            if ($prod && $menge > $prod['stock']) {
                $menge = $prod['stock'];
                $error = "Maximal " . $prod['stock'] . " Stk. verfügbar.";
            }
            $_SESSION['cart'][$product_id] = $menge;
        }
    } elseif ($action === 'remove') {
        $product_id = (int)($_POST['product_id'] ?? 0);
        unset($_SESSION['cart'][$product_id]);
        $msg = "Artikel wurde aus dem Warenkorb entfernt.";
    } elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
        $msg = "Warenkorb wurde geleert.";
    }
}

$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart']) && $pdo) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        if ($qty > $p['stock']) {
            $qty = $p['stock'];
            $_SESSION['cart'][$p['id']] = $qty;
        }
        if ($qty > 0) {
            $subtotal = $p['price'] * $qty;
            $total += $subtotal;
            $cart_items[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'product_number' => $p['product_number'],
                'price' => $p['price'],
                'stock' => $p['stock'],
                'quantity' => $qty,
                'subtotal' => $subtotal
            ];
        }
    }
}
?>

<section class="container">
    <div class="cart-wrapper">
        <h2 class="cart-title">Warenkorb</h2>
        <p class="cart-subtitle"><?php echo htmlspecialchars($_SESSION['customer_name']); ?> (<?php echo htmlspecialchars($_SESSION['customer_number']); ?>)</p>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <div class="cart-empty">
                <p>Ihr Warenkorb ist leer.</p>
                <a href="products.php" class="btn" style="margin-top: 20px;">Jetzt einkaufen</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                            <span class="cart-item-nr"><?php echo htmlspecialchars($item['product_number']); ?></span>
                            <span class="cart-item-price"><?php echo number_format($item['price'], 2, ',', '.'); ?> &euro;/Stk.</span>
                        </div>
                        <div class="cart-item-actions">
                            <form action="cart.php" method="POST" class="cart-qty-form">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="menge" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" class="cart-qty-input">
                                <button type="submit" class="btn-cart-update">Aktualisieren</button>
                            </form>
                            <form action="cart.php" method="POST" class="cart-remove-form">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn-cancel">Entfernen</button>
                            </form>
                        </div>
                        <div class="cart-item-subtotal">
                            <strong><?php echo number_format($item['subtotal'], 2, ',', '.'); ?> &euro;</strong>
                            <?php if ($item['quantity'] >= $item['stock']): ?>
                                <span class="stock-warning">Maximale Anzahl erreicht</span>
                            <?php elseif ($item['stock'] <= 5): ?>
                                <span class="stock-warning">Nur noch <?php echo $item['stock']; ?> Stk. verfügbar!</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Artikel gesamt</span>
                    <span><?php echo array_sum(array_column($cart_items, 'quantity')); ?> Stk.</span>
                </div>
                <div class="summary-row summary-total">
                    <span>Gesamtbetrag</span>
                    <strong><?php echo number_format($total, 2, ',', '.'); ?> &euro;</strong>
                </div>
            </div>

            <div class="cart-actions">
                <a href="products.php" class="btn btn-secondary">Weiter einkaufen</a>
                <a href="order.php" class="btn btn-checkout-link">Zur Kasse</a>
                <form action="cart.php" method="POST" style="display:inline; max-width:none; margin:0; padding:0; background:none;">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="btn-cancel" onclick="return confirm('Warenkorb wirklich leeren?');">Warenkorb leeren</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
