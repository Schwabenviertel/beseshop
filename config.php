<?php
// Öffnendes PHP-Tag für den Beginn des Skripts
// DB Einstellungen: Definition der Zugangsdaten für die MySQL-Datenbank
$host = 'localhost'; // Der Hostname des Datenbankservers (lokal meist 'localhost')
$db   = 'beseshop'; // Der Name der zu verwendenden Datenbank
$user = 'root'; // Der Benutzername für den Datenbankzugriff
$pass = ''; // Das Passwort für den Datenbankzugriff (bei XAMPP standardmäßig leer)
$charset = 'utf8mb4'; // Der Zeichensatz für die Datenbankverbindung (unterstützt Sonderzeichen)

// Erstellung des Data Source Name (DSN) für die PDO-Verbindung
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// Konfigurationsoptionen für PDO (Fehlermodus, Standard-Abruftyp, Emulation deaktivieren)
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Wirft Ausnahmen bei Fehlern
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Gibt Ergebnisse als assoziatives Array zurück
    PDO::ATTR_EMULATE_PREPARES   => false, // Deaktiviert die Emulation von Prepared Statements
];

// Versuch, eine Verbindung zur Datenbank aufzubauen
try {
     // Erzeugt ein neues PDO-Objekt mit den definierten Zugangsdaten und Optionen
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Fängt Fehler ab, falls die Datenbankverbindung fehlschlägt (z.B. DB existiert noch nicht)
     // Verhindert den Absturz der Seite bei der ersten Anzeige
     // Setzt die Variable $pdo auf null, um später im Code darauf prüfen zu können
     $pdo = null;
}

// Startet eine neue oder führt eine bestehende Sitzung fort
session_start();

// Sprache: Logik zur Auswahl der Anzeigesprache (Deutsch/Schwäbisch oder Englisch)
if (isset($_GET['lang'])) {
    // Falls ein 'lang'-Parameter in der URL übergeben wurde, wird dieser in der Session gespeichert
    $_SESSION['lang'] = $_GET['lang'];
}

// Prüft, ob bereits eine Sprache in der Session festgelegt wurde
if (!isset($_SESSION['lang'])) {
    // Falls nicht, wird standardmäßig 'de_sw' (Schwäbisch) als Sprache eingestellt
    $_SESSION['lang'] = 'de_sw';
}

// Zuweisung der aktuellen Sprache aus der Session an die Variable $lang
$lang = $_SESSION['lang'];
// Schließendes PHP-Tag
?>
