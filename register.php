<?php
/**
 *                           _  __            _
 *  _ __ ___  _   _ ___  ___(_)/ _| __ _ _ __(_)
 * | '_ ` _ \| | | / __|/ __| | |_ / _` | '__| |
 * | | | | | | |_| \__ \ (__| |  _| (_| | |  | |
 * |_| |_| |_|\__,_|___/\___|_|_|  \__,_|_|  |_|
 *
 * Registrierungsseite für Neukunden.
 * Der Kunde gibt seine persönlichen Daten ein.
 * Nach erfolgreicher Registrierung wird automatisch eine Kundennummer
 * generiert und der Kunde eingeloggt.
 */
include 'header.php';

$msg   = "";
$error = "";

// Registrierungsformular verarbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST" && $pdo) {
    $vname = $_POST['vname'];
    $nname = $_POST['nname'];
    $mail  = $_POST['mail'];
    $pw    = password_hash($_POST['pw'], PASSWORD_DEFAULT);
    $str   = $_POST['str'];
    $plz   = $_POST['plz'];
    $ort   = $_POST['ort'];

    // Eindeutige Kundennummer erzeugen (z.B. K12345)
    $k_nr = "K" . rand(10000, 99999);

    // Prüfen ob E-Mail bereits vergeben ist
    $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
    $stmt->execute([$mail]);
    if ($stmt->fetch()) {
        $error = "Die E-Mail-Adress isch scho regischdriert!";
    } else {
        try {
            // Neuen Kunden in die Datenbank einfügen
            $sql = "INSERT INTO customers (customer_number, first_name, last_name, email, password, street, zip_code, city) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$k_nr, $vname, $nname, $mail, $pw, $str, $plz, $ort]);

            // Session starten und zum Produktkatalog weiterleiten
            $_SESSION['customer_id']     = $pdo->lastInsertId();
            $_SESSION['customer_number'] = $k_nr;
            $_SESSION['customer_name']   = $vname . ' ' . $nname;

            header("Location: products.php");
            exit;
        } catch (PDOException $e) {
            $error = "Fehler bei dr Regischdrierung. Bitte nomal brobiara.";
        }
    }
}
?>

<section class="container">
    <h2 style="text-align: center; margin-top: 40px;">Regischdrierung</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label>Vorname</label>
            <input type="text" name="vname" required>
        </div>
        <div class="form-group">
            <label>Nachname</label>
            <input type="text" name="nname" required>
        </div>
        <div class="form-group">
            <label>E-Mail</label>
            <input type="email" name="mail" required>
        </div>
        <div class="form-group">
            <label>Passwort</label>
            <input type="password" name="pw" required>
        </div>
        <div class="form-group">
            <label>Schdroß</label>
            <input type="text" name="str" required>
        </div>
        <div class="form-group">
            <label>PLZ</label>
            <input type="text" name="plz" required>
        </div>
        <div class="form-group">
            <label>Schdadt</label>
            <input type="text" name="ort" required>
        </div>
        <button type="submit" class="btn-submit">Regischdriera</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">
        Scho regischdriert? <a href="login.php">Zum Eilogga</a>
    </p>
</section>

<?php include 'footer.php'; ?>
