<?php
session_start();
include "auth.php";
requireLogin();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $class = strtolower(trim($_POST["class"] ?? ""));

    if ($name == "" || strlen($name) > 30) {
        $error = "Enter a character name (30 characters or less).";
    }
    else if (!in_array($class, array("warrior", "mage", "rogue"))) {
        $error = "Choose a character class.";
    }
    else {
        $strength = 2;
        $magic = 2;
        $speed = 2;

        if ($class == "warrior") {
            $strength = 3;
            $magic = 1;
        }
        else if ($class == "mage") {
            $strength = 1;
            $magic = 3;
        }
        else {
            $magic = 1;
            $speed = 3;
        }

        $_SESSION["hero"] = array(
            "name" => $name,
            "class" => ucfirst($class),
            "strength" => $strength,
            "magic" => $magic,
            "speed" => $speed
        );

        // new game when making new character
        $gameKeys = array("location", "inventory", "searched", "room_items",
            "door_open", "monster_defeated", "forest_monster_defeated",
            "king_defeated", "village_visited", "game_over", "game_finished",
            "health", "xp", "history", "choices");
        foreach ($gameKeys as $key) {
            unset($_SESSION[$key]);
        }

        header("Location: game.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPRPG - Character Creation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>PHPRPG</h1>
        <p>The Cursed Kingdom</p>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="game.php">Game</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="character.php">Character Creation</a>
        <a href="logout.php">Logout</a>
    </nav>
    <main>
        <h2>Create Character</h2>
        <?php if ($error != "") { ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>
        <form action="character.php" method="post">
            <p><label for="name">Character Name:</label></p>
            <input type="text" id="name" name="name" maxlength="30" required>
            <p><label for="class">Class:</label></p>
            <select id="class" name="class" required>
                <option value="">Choose class</option>
                <option value="warrior">Warrior</option>
                <option value="mage">Mage</option>
                <option value="rogue">Rogue</option>
            </select>
            <p><button type="submit">Create Character</button></p>
        </form>
        <p>Warrior: Strength 3, Magic 1, Speed 2</p>
        <p>Mage: Strength 1, Magic 3, Speed 2</p>
        <p>Rogue: Strength 2, Magic 1, Speed 3</p>
    </main>
    <footer><p>PHPRPG - Joe Turner and Shozab Mirza</p></footer>
</body>
</html>
