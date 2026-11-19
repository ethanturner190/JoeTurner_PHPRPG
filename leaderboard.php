<?php
session_start();
include "auth.php";
requireLogin();

$scores = $_SESSION["leaderboard"] ?? array();

// most xp is top
usort($scores, function($a, $b) {
    return $b["xp"] <=> $a["xp"];
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPRPG - Leaderboard</title>
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
        <a href="logout.php">Logout</a>
        <a href="character.php">Character Creation</a>
    </nav>
    <main>
        <h2>Leaderboard</h2>
        <p>Completed games from this browser session.</p>
        <?php if (count($scores) == 0) { ?>
            <p>No completed games yet. Finish the story to get a score.</p>
        <?php } else { ?>
            <table border="1" cellpadding="10">
                <tr>
                    <th>Player</th>
                    <th>Character</th>
                    <th>Class</th>
                    <th>XP</th>
                    <th>Ending</th>
                </tr>
                <?php foreach ($scores as $score) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($score["player"]); ?></td>
                        <td><?php echo htmlspecialchars($score["character"]); ?></td>
                        <td><?php echo htmlspecialchars($score["class"]); ?></td>
                        <td><?php echo (int)$score["xp"]; ?></td>
                        <td><?php echo htmlspecialchars($score["ending"]); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
        <p><a href="game.php">Back to Game</a></p>
    </main>
    <footer><p>PHPRPG - Joe Turner and Shozab Mirza</p></footer>
</body>
</html>
