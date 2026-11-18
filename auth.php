<?php
// Demo accounts, does have persistant accounts
function requireLogin() {
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit;
    }
}
?>
