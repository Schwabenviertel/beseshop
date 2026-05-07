<?php
/**
 *                           _  __            _
 *  _ __ ___  _   _ ___  ___(_)/ _| __ _ _ __(_)
 * | '_ ` _ \| | | / __|/ __| | |_ / _` | '__| |
 * | | | | | | |_| \__ \ (__| |  _| (_| | |  | |
 * |_| |_| |_|\__,_|___/\___|_|_|  \__,_|_|  |_|
 *
 * Startseite des BESE.CO Webshops.
 * Zeigt den Hero-Bereich und die drei Verkaufsargumente an.
 */
include 'header.php';
?>

<section class="hero">
    <div class="container">
        <h1>Griaß di bei BESE.CO</h1>
        <p>Guck dr onsre handgmachda Besa aus em Schwobaland aa - Qualidäd seid Generaziona!</p>
        <a href="products.php" class="btn">Jetzd eikaufa</a>
    </div>
</section>

<section class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 40px;">Warom bei ons kaufa?</h2>
    </div>

    <div class="features">
        <div class="feature-card">
            <div style="font-size: 50px;">&#127795;</div>
            <h3>Nachhaldich</h3>
            <p>Nadürliche Materialiga aus em Schwarzwald.</p>
        </div>
        <div class="feature-card">
            <div style="font-size: 50px;">&#129330;</div>
            <h3>Handgmachd</h3>
            <p>Jeder Besa isch a Unikad!</p>
        </div>
        <div class="feature-card">
            <div style="font-size: 50px;">&#9889;</div>
            <h3>Schnelle Lieferung</h3>
            <p>Zuaverläsig ond pünktlich bei dir.</p>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
