<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPRPG</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <header>
        <h1>PHPRPG</h1>
        <p>The Cursed Kingdom</p>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION["username"])) { ?>
            <a href="game.php">Game</a>
            <a href="character.php">Character Creation</a>
            <a href="logout.php">Logout</a>
        <?php } else { ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php } ?>
        <a href="leaderboard.php">Leaderboard</a>
    </nav>

    <main>
        <h2>Welcome to PHPRPG</h2>

        <p>
            The kingdom has fallen under a mysterious curse.
            The king has disappeared and monsters now walk freely
            within the walls of the city.
        </p>

        <p>
            You wake up inside a prison cell with no memory
            of how you got there. Escape your cell, explore
            the city, and find out what happened to the kingdom.
        </p>

        <h3>How to Play</h3>

        <p>
            PHPRPG is a text based adventure game where you
            type commands to explore different areas and rooms. Text 
            will describe your surroundings. You will type
            commands to interact with the world. 
            Your choices affect what happens throughout
            the story and your fate. To see the list of
            commands, type "help".
        </p>

        <p>Some commands you can use:</p>

        <ul>
            <li>look around</li>
            <li>take item</li>
            <li>open door</li>
            <li>go north / south / east / west</li>
            <li>open bag</li>
            <li>use [item name]</li>
            <li>fight</li>
            <li>help</li>
        </ul>

        <a href="game.php" class="start-button">Start Game</a>
        <a href="character.php" class="start-button">Create Character</a>

    </main>

    <footer>
        <p>PHPRPG - Joe Turner and Shozab Mirza</p>
    </footer>

</body>
</html>
