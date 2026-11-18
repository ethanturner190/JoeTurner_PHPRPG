<?php
// demo accounts, does NOT have persistant accounts, redirects
function requireLogin() {
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit;
    }
}
?>
