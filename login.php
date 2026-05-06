<?php
/**
 * Login-Seite für registrierte Kunden.
 * Prüft E-Mail und Passwort gegen die Datenbank.
 * Bei Erfolg wird die Session gesetzt und zum Produktkatalog weitergeleitet.
 */
include 'header.php';

$error = "";

// Login-Formular verarbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST" && $pdo) {
    $mail = $_POST['mail'];
    $pw   = $_POST['pw'];

    // Kunde anhand der E-Mail suchen
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$mail]);
    $kunde = $stmt->fetch();

    // Passwort prüfen und Session starten
    if ($kunde && password_verify($pw, $kunde['password'])) {
        $_SESSION['customer_id']     = $kunde['id'];
        $_SESSION['customer_number'] = $kunde['customer_number'];
        $_SESSION['customer_name']   = $kunde['first_name'] . ' ' . $kunde['last_name'];
        header("Location: products.php");
        exit;
    } else {
        $error = "E-Mail oder Passwort falsch!";
    }
}
?>

<section class="container">
    <h2 style="text-align: center; margin-top: 40px;">Login</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label>E-Mail</label>
            <input type="email" name="mail" required>
        </div>
        <div class="form-group">
            <label>Passwort</label>
            <input type="password" name="pw" required>
        </div>
        <button type="submit" class="btn-submit">Einloggen</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">
        Noch kein Konto? <a href="register.php">Jetzt registrieren</a>
    </p>
</section>

<?php include 'footer.php'; ?>
