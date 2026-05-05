<?php
/**
 * Logout-Skript.
 * Beendet die aktive Session und leitet zur Startseite weiter.
 */
include_once 'config.php';

session_destroy();
header("Location: index.php");
exit;
?>
