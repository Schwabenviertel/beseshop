<?php 
// Bindet den Kopfbereich der Webseite (Navigation, HTML-Grundgerüst) ein
include 'header.php'; 
?>

<!-- Beginn des Hero-Bereichs (Haupteinstieg der Seite) -->
<section class="hero">
    <div class="container"> <!-- Container zur Zentrierung und Begrenzung der Inhaltsbreite -->
        <!-- Zeigt die übersetzte Willkommensüberschrift an -->
        <h1><?php echo t('welcome'); ?></h1>
        <!-- Zeigt den übersetzten Untertitel/Begrüßungstext an -->
        <p><?php echo t('sub_welcome'); ?></p>
        <!-- Button-Link zur Produktseite mit übersetztem Text -->
        <a href="products.php" class="btn"><?php echo t('shop_now'); ?></a>
    </div> <!-- Ende Hero-Container -->
</section> <!-- Ende Hero-Bereich -->

<!-- Bereich für die Vorzüge/Features des Geschäfts -->
<section class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <!-- Container für die zentrierte Überschrift des Feature-Bereichs -->
    <div style="text-align: center; margin-bottom: 30px;">
        <!-- Zeigt die übersetzte Überschrift "Warum wir?" an -->
        <h2 style="font-size: 40px;"><?php echo t('why_us'); ?></h2>
    </div> <!-- Ende Überschrift-Container -->
    
    <div class="features"> <!-- Flexbox-Container für die einzelnen Feature-Karten -->
        <div class="feature-card"> <!-- Erste Feature-Karte: Nachhaltigkeit -->
            <div style="font-size: 50px;">🌳</div> <!-- Baum-Emoji als Symbol -->
            <h3><?php echo t('sustainable'); ?></h3> <!-- Übersetzte Überschrift: Nachhaltigkeit -->
            <p><?php echo t('sustainable_text'); ?></p> <!-- Übersetzter Beschreibungstext zur Nachhaltigkeit -->
        </div> <!-- Ende Karte 1 -->
        
        <div class="feature-card"> <!-- Zweite Feature-Karte: Handarbeit -->
            <div style="font-size: 50px;">🤲</div> <!-- Hände-Emoji als Symbol -->
            <h3><?php echo t('handmade'); ?></h3> <!-- Übersetzte Überschrift: Handgemacht -->
            <p><?php echo t('handmade_text'); ?></p> <!-- Übersetzter Beschreibungstext zur Handarbeit -->
        </div> <!-- Ende Karte 2 -->
        
        <div class="feature-card"> <!-- Dritte Feature-Karte: Geschwindigkeit -->
            <div style="font-size: 50px;">⚡</div> <!-- Blitz-Emoji als Symbol -->
            <h3><?php echo t('fast'); ?></h3> <!-- Übersetzte Überschrift: Schnell -->
            <p><?php echo t('fast_text'); ?></p> <!-- Übersetzter Beschreibungstext zum Versand -->
        </div> <!-- Ende Karte 3 -->
    </div> <!-- Ende Features-Container -->
</section> <!-- Ende Feature-Bereich -->

<?php 
// Bindet den Fußbereich der Webseite (Copyright, Schließung der Tags) ein
include 'footer.php'; 
?>
