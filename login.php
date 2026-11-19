<?php
session_start();
include "auth.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = strtolower(trim($_POST["username"] ?? ""));
    $password = $_POST["password"] ?? "";
    if (isset($_SESSION["registered_user"], $_SESSION["registered_password"]) &&
        $username == $_SESSION["registered_user"] &&
        password_verify($password, $_SESSION["registered_password"])) {
        session_regenerate_id(true);
        if (!isset($_SESSION["username"]) || $_SESSION["username"] != $username) {
            unset($_SESSION["hero"]);
        }
        $_SESSION["username"] = $username;
        header("Location: game.php");
        exit;
    }
    else {
        $error = "Incorrect username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPRPG - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header><h1>PHPRPG</h1><p>The Cursed Kingdom</p></header>
    <nav><a href="index.php">Home</a> <a href="register.php">Register</a></nav>
    <main>
        <h2>Login</h2>
        <p>Log in using the account you registered in this browser session.</p>
        <?php if ($error != "") { ?><p><?php echo htmlspecialchars($error); ?></p><?php } ?>
        <form action="login.php" method="post">
            <p><label for="username">Username:</label></p>
            <input type="text" id="username" name="username" required autofocus>
            <p><label for="password">Password:</label></p>
            <input type="password" id="password" name="password" required>
            <p><button type="submit">Login</button></p>
        </form>
        <p>New player? <a href="register.php">Register</a></p>
    </main>
    <footer><p>PHPRPG - Joe Turner and Shozab Mirza</p></footer>
</body>
</html>
