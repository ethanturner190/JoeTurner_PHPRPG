
<?php
session_start();
include "story.php";

// Set up a new game
if (!isset($_SESSION["location"])) {
    $_SESSION["history"] = array();
    $_SESSION["location"] = "cell";
    $_SESSION["inventory"] = array();
    $_SESSION["searched"] = array();
    $_SESSION["room_items"] = array();
    $_SESSION["door_open"] = false;
    $_SESSION["monster_defeated"] = false;
    $_SESSION["game_over"] = false;
    $_SESSION["health"] = 100;
    $_SESSION["xp"] = 0;

    foreach ($story as $room => $info) {
        $_SESSION["room_items"][$room] = $info["item"];
    }
}

$message = "";

if (!isset($_SESSION["history"])) {
    $_SESSION["history"] = array();
}

if (count($_SESSION["history"]) == 0) {
    $_SESSION["history"][] = $story[$_SESSION["location"]]["description"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $command = strtolower(trim($_POST["command"] ?? ""));
    $room = $_SESSION["location"];

    // Restart the game
    if (isset($_POST["restart"])) {
        session_unset();
        header("Location: game.php");
        exit;
    }

    if ($_SESSION["game_over"]) {
        $message = "Game Over. Start a new game to try again.";
    }

    else if ($command == "help") {
        $message = "Commands: look around, take item,
        open door, go north, go south, go east, go west,
        open bag, drop [item], use [item], fight,
        talk, stats, help";
    }

    else if ($command == "look around") {
        $_SESSION["searched"][$room] = true;

        $item = $_SESSION["room_items"][$room];

        if ($item != "") {
            $message = "You search the area and find a " . $item . ".";
        }
        else {
            $message = "You search the area but find nothing.";
        }
    }

    else if ($command == "take item") {
        $item = $_SESSION["room_items"][$room];

        if ($item == "") {
            $message = "There is nothing to take.";
        }
        else if (!isset($_SESSION["searched"][$room])) {
            $message = "You should search the room first.";
        }
        else {
            $_SESSION["inventory"][] = $item;
            $_SESSION["room_items"][$room] = "";
            $message = "You picked up the " . $item . ".";
        }
    }

    else if ($command == "open bag") {
        if (count($_SESSION["inventory"]) == 0) {
            $message = "Your bag is empty.";
        }
        else {
            $message = "Your bag contains: " .
                implode(", ", $_SESSION["inventory"]);
        }
    }

    else if (substr($command, 0, 5) == "drop ") {
        $item = trim(substr($command, 5));

        if (!in_array($item, $_SESSION["inventory"])) {
            $message = "You do not have that item.";
        }
        else if ($_SESSION["room_items"][$room] != "") {
            $message = "There is already an item here.";
        }
        else {
            $position = array_search($item, $_SESSION["inventory"]);
            unset($_SESSION["inventory"][$position]);
            $_SESSION["inventory"] = array_values($_SESSION["inventory"]);

            $_SESSION["room_items"][$room] = $item;
            $message = "You dropped the " . $item . ".";
        }
    }

    else if (substr($command, 0, 4) == "use ") {
        $item = trim(substr($command, 4));

        if (!in_array($item, $_SESSION["inventory"])) {
            $message = "You do not have that item.";
        }
        else if ($item == "rusty key") {
            $message = "You have a rusty key. It probably opens a door.";
        }
        else if ($item == "old sword") {
            $message = "You hold your old sword. It will be useful in a fight.";
        }
        else {
            $message = "You cannot use that item right now.";
        }
    }

    else if ($command == "open door") {
        if ($room == "cell") {
            if ($_SESSION["door_open"]) {
                $message = "The door is already open.";
            }
            else if (in_array("rusty key", $_SESSION["inventory"])) {
                $_SESSION["door_open"] = true;
                $message = "You've unlocked and opened the door.";
            }
            else {
                $message = "The door is locked. You need a key.";
            }
        }
        else {
            $message = "There is no door to open here.";
        }
    }

    else if (substr($command, 0, 3) == "go ") {
        $direction = trim(substr($command, 3));
        $exits = $story[$room]["exits"];

        if (!isset($exits[$direction])) {
            $message = "You cannot go that way.";
        }
        else if ($room == "cell" &&
                 $direction == "north" &&
                 !$_SESSION["door_open"]) {
            $message = "The door is closed. You need to open it.";
        }
        else {
            $_SESSION["location"] = $exits[$direction];
            $message = "You move " . $direction . ".";
        }
    }

    else if ($command == "fight") {
        if ($room == "courtyard" &&
            !$_SESSION["monster_defeated"]) {

            if (in_array("old sword", $_SESSION["inventory"])) {
                $_SESSION["monster_defeated"] = true;
                $_SESSION["xp"] += 20;
                $message = "You draw your sword and fight the
                cursed soldier. You kill it. You gained 20 XP.";
            }
            else {
                $_SESSION["health"] = 0;
                $_SESSION["game_over"] = true;
                $message = "You attack the cursed soldier without
                a weapon. Now you're dead. Game Over.";
            }
        }
        else {
            $message = "There is nothing to fight here.";
        }
    }

    else if ($command == "talk") {
        $message = "There is nobody here to talk to.";
    }

    else if ($command == "stats") {
        $message = "Health: " . $_SESSION["health"] .
                   " | XP: " . $_SESSION["xp"];
    }

    else {
        $message = "Invalid command. Type help to see commands.";
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST" &&
        !isset($_POST["restart"])) {

        $_SESSION["history"][] = "> " . $command;

        if ($message != "") {
            $_SESSION["history"][] = $message;
        }

        // Add the new room description when moving
        if ($_SESSION["location"] != $room) {
            $newRoom = $_SESSION["location"];
            $_SESSION["history"][] = $story[$newRoom]["description"];
        }
    }

}

// Get the current location
$room = $_SESSION["location"];
$currentStory = $story[$room];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHPRPG - Game</title>
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
    </nav>

    <main>
        <h2>Current Location: <?php echo htmlspecialchars($currentStory["name"]); ?></h2>

        <div class="game-log">
            <?php foreach (array_reverse($_SESSION["history"]) as $entry) { ?>
                <p><?php echo htmlspecialchars($entry); ?></p>
            <?php } ?>
        </div>

        <?php if ($_SESSION["game_over"]) { ?>

            <h3>Game Over</h3>
            <form action="game.php" method="post">
                <button type="submit" name="restart" value="1">
                    Restart Game
                </button>
            </form>

        <?php } else { ?>

            <h3>Enter Command</h3>

            <form action="game.php" method="post">
                <input type="text" name="command" required autofocus>
                <button type="submit">Enter</button>
            </form>

            <p>Type help for a list of commands.</p>

        <?php } ?>

    </main>

    <footer>
        <p>PHPRPG - Joe Turner and Shozab Mirza</p>
    </footer>

</body>
</html>
