<?php 
// Bindet den Kopfbereich der Webseite ein (Navigation, HTML-Grundgerüst)
include 'header.php'; 

// Initialisiert eine leere Variable für Rückmeldungen an den Benutzer
$msg = "";
// Prüft, ob das Formular per POST abgeschickt wurde und ob eine Datenbankverbindung besteht
if ($_SERVER["REQUEST_METHOD"] == "POST" && $pdo) {
    // Speichert die eingegebenen Formulardaten in Variablen
    $vname = $_POST['vname']; // Vorname
    $nname = $_POST['nname']; // Nachname
    $mail = $_POST['mail']; // E-Mail Adresse
    // Verschlüsselt das Passwort sicher mit dem Standard-Algorithmus (Bcrypt)
    $pw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
    $str = $_POST['str']; // Straße
    $plz = $_POST['plz']; // Postleitzahl
    $ort = $_POST['ort']; // Wohnort
    
    // Generiert eine zufällige Kundennummer beginnend mit 'K' und 5 Zufallszahlen
    $k_nr = "K" . rand(10000, 99999);
    
    // Versucht, die Daten in die Datenbank einzufügen
    try {
        // SQL-Befehl zum Einfügen eines neuen Kunden (mit Platzhaltern für Sicherheit)
        $sql = "INSERT INTO customers (customer_number, first_name, last_name, email, password, street, zip_code, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        // Bereitet das SQL-Statement vor (Schutz gegen SQL-Injection)
        $stmt = $pdo->prepare($sql);
        // Führt das Statement mit den tatsächlichen Werten aus
        $stmt->execute([$k_nr, $vname, $nname, $mail, $pw, $str, $plz, $ort]);
        // Setzt eine Erfolgsmeldung inklusive der generierten Kundennummer
        $msg = "Erfolg! Deine Kundennummer: " . $k_nr;
    } catch (PDOException $e) {
        // Falls ein Fehler auftritt (z.B. E-Mail schon vorhanden), wird die Fehlermeldung gespeichert
        $msg = "Fehler: " . $e->getMessage();
    }
}
?>

<!-- Bereich für das Registrierungsformular -->
<section class="container">
    <!-- Zeigt die übersetzte Überschrift für die Registrierung an -->
    <h2 style="text-align: center; margin-top: 40px;"><?php echo t('registration'); ?></h2>
    
    <?php if ($msg): ?>
        <!-- Zeigt die Erfolgs- oder Fehlermeldung an, falls $msg nicht leer ist -->
        <div style="background: #eee; padding: 20px; margin: 20px auto; max-width: 500px; text-align: center;">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <!-- Das Formular schickt die Daten an register.php per POST-Methode -->
    <form action="register.php" method="POST">
        <div class="form-group"> <!-- Gruppe für Vorname -->
            <label>Vorname</label>
            <input type="text" name="vname" required> <!-- Pflichtfeld für Vorname -->
        </div>
        <div class="form-group"> <!-- Gruppe für Nachname -->
            <label>Nachname</label>
            <input type="text" name="nname" required> <!-- Pflichtfeld für Nachname -->
        </div>
        <div class="form-group"> <!-- Gruppe für E-Mail -->
            <label>E-Mail</label>
            <input type="email" name="mail" required> <!-- Pflichtfeld für E-Mail mit Typprüfung -->
        </div>
        <div class="form-group"> <!-- Gruppe für Passwort -->
            <label>Passwort</label>
            <input type="password" name="pw" required> <!-- Pflichtfeld für Passwort (verdeckte Eingabe) -->
        </div>
        <div class="form-group"> <!-- Gruppe für Straße -->
            <label>Straße</label>
            <input type="text" name="str"> <!-- Optionales Feld für die Straße -->
        </div>
        <div class="form-group"> <!-- Gruppe für PLZ -->
            <label>PLZ</label>
            <input type="text" name="plz"> <!-- Optionales Feld für die PLZ -->
        </div>
        <div class="form-group"> <!-- Gruppe für Stadt -->
            <label>Stadt</label>
            <input type="text" name="ort"> <!-- Optionales Feld für die Stadt -->
        </div>
        <!-- Absende-Button mit übersetztem Text -->
        <button type="submit" class="btn-submit"><?php echo t('submit'); ?></button>
    </form>
</section>

<?php 
// Bindet den Fußbereich der Webseite ein
include 'footer.php'; 
?>
