<?php
// Demo accounts, does NOT have persistant accounts
function requireLogin() {
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit;
    }
}
?>
