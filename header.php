<?php 
// Bindet die Konfigurationsdatei einmalig ein, um Zugriff auf $lang und $pdo zu haben
include_once 'config.php'; 
// Bindet die Übersetzungsdatei einmalig ein, um die Funktion t() nutzen zu können
include_once 'translations.php'; 
?>
<!DOCTYPE html> <!-- Definiert das Dokument als HTML5-Dokument -->
<html lang="<?php echo ($lang == 'de_sw' ? 'de' : 'en'); ?>"> <!-- Setzt das Sprachattribut des HTML-Tags basierend auf der gewählten Sprache -->
<head> <!-- Beginn des Kopfbereichs (Metadaten, Titel, Stylesheets) -->
    <meta charset="UTF-8"> <!-- Setzt die Zeichenkodierung auf UTF-8 für korrekte Darstellung von Sonderzeichen -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Sorgt für eine korrekte Skalierung auf mobilen Geräten -->
    <title><?php echo t('title'); ?></title> <!-- Gibt den übersetzten Titel der Webseite aus -->
    <link rel="stylesheet" href="style.css"> <!-- Bindet die externe CSS-Datei für das Design ein -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet"> <!-- Lädt die Schriftart 'Inter' von Google Fonts -->
</head> <!-- Ende des Kopfbereichs -->
<body> <!-- Beginn des sichtbaren Bereichs der Webseite -->
    <header> <!-- Beginn des Seitenkopfs -->
        <nav class="container"> <!-- Navigationsbereich mit Container-Klasse für die Zentrierung -->
            <div class="logo"> <!-- Container für das Firmenlogo -->
                <a href="index.php">BESE.CO</a> <!-- Link zum Logo, führt zurück zur Startseite -->
            </div> <!-- Ende Logo-Container -->
            <ul class="nav-links"> <!-- Ungeordnete Liste für die Hauptnavigationslinks -->
                <li><a href="index.php"><?php echo t('nav_home'); ?></a></li> <!-- Link zur Startseite mit übersetztem Text -->
                <li><a href="products.php"><?php echo t('nav_products'); ?></a></li> <!-- Link zur Produktübersicht mit übersetztem Text -->
                <li><a href="register.php"><?php echo t('nav_register'); ?></a></li> <!-- Link zur Registrierung mit übersetztem Text -->
                <li><a href="order.php"><?php echo t('nav_order'); ?></a></li> <!-- Link zum Bestellen mit übersetztem Text -->
            </ul> <!-- Ende Navigationslinks -->
            <div class="lang-switcher"> <!-- Container für den Sprachumschalter (Deutsch/Englisch) -->
                <!-- Link zum Umschalten auf Deutsch/Schwäbisch, markiert als 'active' wenn aktuell ausgewählt -->
                <a href="?lang=de_sw" class="<?php echo ($lang == 'de_sw' ? 'active' : ''); ?>">DE</a> | 
                <!-- Link zum Umschalten auf Englisch, markiert als 'active' wenn aktuell ausgewählt -->
                <a href="?lang=en" class="<?php echo ($lang == 'en' ? 'active' : ''); ?>">EN</a>
            </div> <!-- Ende Sprachumschalter -->
        </nav> <!-- Ende Navigation -->
    </header> <!-- Ende Seitenkopf -->
    <main> <!-- Beginn des Hauptinhaltsbereichs -->
