<?php
/**
 * Startseite des BESE.CO Webshops.
 * Zeigt den Hero-Bereich und die drei Verkaufsargumente an.
 */
include 'header.php';
?>

<section class="hero">
    <div class="container">
        <h1>Willkommen bei BESE.CO</h1>
        <p>Entdecke unsere handgefertigten Besen aus dem Schwabenland - Qualität seit Generationen!</p>
        <a href="products.php" class="btn">Jetzt einkaufen</a>
    </div>
</section>

<section class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 40px;">Warum bei uns kaufen?</h2>
    </div>

    <div class="features">
        <div class="feature-card">
            <div style="font-size: 50px;">&#127795;</div>
            <h3>Nachhaltig</h3>
            <p>Natürliche Materialien aus dem Schwarzwald.</p>
        </div>
        <div class="feature-card">
            <div style="font-size: 50px;">&#129330;</div>
            <h3>Handgemacht</h3>
            <p>Jeder Besen ist ein Unikat!</p>
        </div>
        <div class="feature-card">
            <div style="font-size: 50px;">&#9889;</div>
            <h3>Schnelle Lieferung</h3>
            <p>Zuverlässig und pünktlich bei dir.</p>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
