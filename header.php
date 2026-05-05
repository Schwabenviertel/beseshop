<?php
/**
 * Header-Datei fuer alle Seiten des BESE.CO Webshops.
 * Bindet die Konfiguration ein und rendert Navigation inkl. HTML-Kopf.
 * Navigation passt sich an: eingeloggte Kunden sehen andere Links als Gaeste.
 */
include_once 'config.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BESE.CO - Schwaebische Bese</title>
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
