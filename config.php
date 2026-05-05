<?php
/**
 * Konfigurationsdatei fuer den BESE.CO Webshop.
 * Stellt die Datenbankverbindung via PDO her und startet die Session.
 *
 * Voraussetzung: XAMPP mit Apache und MySQL muss laufen.
 * Die Datenbank wird mit database.sql erstellt.
 */

session_start();

// Datenbank-Zugangsdaten (Standard fuer XAMPP)
$host    = 'localhost';
$db      = 'beseshop';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

// PDO-Verbindung aufbauen
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Verbindung fehlgeschlagen — $pdo bleibt null, Seiten zeigen Hinweis
    $pdo = null;
}
?>
