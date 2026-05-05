<?php
// Öffnendes PHP-Tag für den Beginn des Skripts
// Definition des mehrdimensionalen Arrays $translations, das alle Texte in verschiedenen Sprachen enthält
$translations = [
    // Sprachpaket für Deutsch/Schwäbisch ('de_sw')
    'de_sw' => [
        'title' => 'BESE.CO - Premium Schwäbische Bese', // Titel der Webseite
        'welcome' => 'Find Bese That Matches Your Kehrwoch', // Willkommensüberschrift
        'sub_welcome' => 'Entdecke onsri sorgfältig gfertigte Bese, gmacht zum dei Individualität rauszbringe ond dei Kehrwoch perfekt z\'mache.', // Einleitungstext
        'shop_now' => 'Jetzat oikaufe', // Text für den Shop-Button
        'why_us' => 'Warum bei uns kaufe?', // Überschrift für den Feature-Bereich
        'sustainable' => 'Nachhaltig halt!', // Titel Feature 1 (Nachhaltigkeit)
        'sustainable_text' => 'Natürliche Materiale aus\'m Schwarzwald - net so\'n Plastik-Krempel!', // Text Feature 1
        'handmade' => 'Handgmacht', // Titel Feature 2 (Handarbeit)
        'handmade_text' => 'Mit schwäbischer Sorgfalt hergstellt - jeder Bese a Unikat!', // Text Feature 2
        'fast' => 'Flink wie dr Wind', // Titel Feature 3 (Geschwindigkeit)
        'fast_text' => 'Pünktlich vor deiner Kehrwoch - des kannsch dr drauf verlassa!', // Text Feature 3
        'nav_home' => 'Dahoim', // Navigationslink: Startseite
        'nav_products' => 'Alli Bese', // Navigationslink: Produkte
        'nav_register' => 'Regischtriere', // Navigationslink: Registrierung
        'nav_order' => 'Bestella', // Navigationslink: Bestellung
        'customer_no' => 'Kundenummer', // Label für Kundennummer
        'product_no' => 'Artikelnumer', // Label für Artikelnummer
        'footer_text' => 'Premium-Bese aus Baden-Württemberg - mit Lieb handgmacht!', // Text im Fußbereich
        'registration' => 'Neu hier? Dann regischtrier di!', // Überschrift Registrierungsformular
        'order_form' => 'Bestell-Formular', // Überschrift Bestellformular
        'submit' => 'Abschicka', // Text für Absende-Buttons
        'lang_en' => 'English', // Sprachbezeichnung Englisch
        'lang_de' => 'Schwäbisch' // Sprachbezeichnung Deutsch/Schwäbisch
    ],
    // Sprachpaket für Englisch ('en')
    'en' => [
        'title' => 'BESE.CO - Premium Swabian Brooms', // Website title
        'welcome' => 'Find Brooms That Match Your Cleaning Day', // Welcome heading
        'sub_welcome' => 'Discover our carefully crafted brooms, made to bring out your individuality and make your cleaning perfect.', // Intro text
        'shop_now' => 'Shop Now', // Shop button text
        'why_us' => 'Why buy from us?', // Feature section heading
        'sustainable' => 'Truly Sustainable!', // Feature 1 title
        'sustainable_text' => 'Natural materials from the Black Forest - no plastic rubbish!', // Feature 1 text
        'handmade' => 'Handmade', // Feature 2 title
        'handmade_text' => 'Produced with Swabian care - every broom is unique!', // Feature 2 text
        'fast' => 'Fast as the Wind', // Feature 3 title
        'fast_text' => 'Punctually before your cleaning day - you can rely on that!', // Feature 3 text
        'nav_home' => 'Home', // Nav link: Home
        'nav_products' => 'All Brooms', // Nav link: Products
        'nav_register' => 'Register', // Nav link: Register
        'nav_order' => 'Order', // Nav link: Order
        'customer_no' => 'Customer Number', // Label for customer number
        'product_no' => 'Product Number', // Label for product number
        'footer_text' => 'Premium brooms from Baden-Württemberg - handmade with love!', // Footer text
        'registration' => 'New here? Then register!', // Registration heading
        'order_form' => 'Order Form', // Order form heading
        'submit' => 'Submit', // Submit button text
        'lang_en' => 'English', // Language name English
        'lang_de' => 'German/Swabian' // Language name German/Swabian
    ]
];

// Definition der Funktion t(), die einen Schlüssel in die aktuelle Sprache übersetzt
function t($key) {
    // Zugriff auf die globalen Variablen $translations und $lang
    global $translations, $lang;
    // Gibt die Übersetzung für den Schlüssel zurück, falls vorhanden, sonst den Schlüssel selbst
    return $translations[$lang][$key] ?? $key;
}
// Schließendes PHP-Tag
?>
