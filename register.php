<?php
session_start();
include "auth.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = strtolower(trim($_POST["username"] ?? ""));
    $password = $_POST["password"] ?? "";

    if (!preg_match('/^[a-z0-9_]{3,20}$/', $username)) {
        $error = "Username must be 3-20 letters, numbers, or underscores.";
    }
    else if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    }
    else {
        // One temporary account per browser session.
        if (isset($_SESSION["registered_user"]) && $_SESSION["registered_user"] == $username) {
            $error = "That username is already registered in this session.";
        }
        else {
            session_regenerate_id(true);
            $_SESSION["registered_user"] = $username;
            $_SESSION["registered_password"] = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION["username"] = $username;
            unset($_SESSION["hero"]);
            header("Location: character.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPRPG - Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header><h1>PHPRPG</h1><p>The Cursed Kingdom</p></header>
    <nav><a href="index.php">Home</a> <a href="login.php">Login</a></nav>
    <main>
        <h2>Register</h2>
        <p>Demo account: saved only while your browser session is active.</p>
        <?php if ($error != "") { ?><p><?php echo htmlspecialchars($error); ?></p><?php } ?>
        <form action="register.php" method="post">
            <p><label for="username">Username:</label></p>
            <input type="text" id="username" name="username" maxlength="20" required>
            <p><label for="password">Password:</label></p>
            <input type="password" id="password" name="password" minlength="8" required>
            <p><button type="submit">Register</button></p>
        </form>
        <p>Already registered? <a href="login.php">Login</a></p>
    </main>
    <footer><p>PHPRPG - Joe Turner and Shozab Mirza</p></footer>
</body>
</html>
