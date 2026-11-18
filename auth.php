<?php
// Demo accounts are kept in the PHP session. No database or file writes.
function requireLogin() {
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit;
    }
}
?>
