<?php
/**
 *                           _  __            _
 *  _ __ ___  _   _ ___  ___(_)/ _| __ _ _ __(_)
 * | '_ ` _ \| | | / __|/ __| | |_ / _` | '__| |
 * | | | | | | |_| \__ \ (__| |  _| (_| | |  | |
 * |_| |_| |_|\__,_|___/\___|_|_|  \__,_|_|  |_|
 *
 * Header-Datei für alle Seiten des BESE.CO Webshops.
 * Bindet die Konfiguration ein und rendert Navigation inkl. HTML-Kopf.
 * Navigation passt sich an: eingeloggte Kunden sehen andere Links als Gäste.
 */
// Konfiguration laden (DB-Verbindung und Session-Start)
include_once 'config.php';
?>
<!-- HTML-Grundgerüst: Meta-Tags, Stylesheet und responsive Viewport -->
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BESE.CO - Schwäbische Besa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">
                <a href="index.php">BESE.CO</a>
            </div>
            <!-- Navigation: unterschiedliche Links für eingeloggte Kunden und Gäste -->
            <ul class="nav-links">
                <li><a href="index.php">Startseit</a></li>
                <li><a href="products.php">Produkd</a></li>
                <?php if (isset($_SESSION['customer_id'])): ?>
                    <?php $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
                    <li><a href="cart.php">Warakorb<?php if ($cart_count > 0): ?> <span class="cart-badge"><?php echo $cart_count; ?></span><?php endif; ?></a></li>
                    <li><a href="order.php">Bschdella</a></li>
                    <li><a href="my_orders.php">Mei Bschdellonga</a></li>
                    <li><a href="logout.php">Abmolda (<?php echo htmlspecialchars($_SESSION['customer_name']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="register.php">Regischdrierung</a></li>
                    <li><a href="login.php">Eilogga</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
