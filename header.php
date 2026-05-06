<?php
/**
 * Header-Datei für alle Seiten des BESE.CO Webshops.
 * Bindet die Konfiguration ein und rendert Navigation inkl. HTML-Kopf.
 * Navigation passt sich an: eingeloggte Kunden sehen andere Links als Gäste.
 */
include_once 'config.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BESE.CO - Schwäbische Bese</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">
                <a href="index.php">BESE.CO</a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Startseite</a></li>
                <li><a href="products.php">Produkte</a></li>
                <?php if (isset($_SESSION['customer_id'])): ?>
                    <?php $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
                    <li><a href="cart.php">Warenkorb<?php if ($cart_count > 0): ?> <span class="cart-badge"><?php echo $cart_count; ?></span><?php endif; ?></a></li>
                    <li><a href="order.php">Bestellen</a></li>
                    <li><a href="my_orders.php">Meine Bestellungen</a></li>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['customer_name']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="register.php">Registrierung</a></li>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
