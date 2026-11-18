<?php
session_start();
include "auth.php";
requireLogin();
include "story.php";

// Make a character before starting the game
if (!isset($_SESSION["hero"])) {
    header("Location: character.php");
    exit;
}

// Only clear game data, not login information
$gameKeys = array(
    "location", "inventory", "searched", "room_items", "door_open",
    "monster_defeated", "forest_monster_defeated", "king_defeated",
    "village_visited", "game_over", "game_finished", "health", "xp",
    "history", "choices"
);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["restart"])) {
    foreach ($gameKeys as $key) {
        unset($_SESSION[$key]);
    }
    header("Location: game.php");
    exit;
}

// Start a new game
if (!isset($_SESSION["location"])) {
    $_SESSION["location"] = "cell";
    $_SESSION["inventory"] = array();
    $_SESSION["searched"] = array();
    $_SESSION["room_items"] = array();
    $_SESSION["door_open"] = false;
    $_SESSION["monster_defeated"] = false;
    $_SESSION["forest_monster_defeated"] = false;
    $_SESSION["king_defeated"] = false;
    $_SESSION["village_visited"] = false;
    $_SESSION["game_over"] = false;
    $_SESSION["game_finished"] = false;
    $_SESSION["health"] = 100;
    $_SESSION["xp"] = 0;
    $_SESSION["history"] = array($story["cell"]["description"]);
    $_SESSION["choices"] = array();

    foreach ($story as $room => $info) {
        $_SESSION["room_items"][$room] = $info["item"];
    }
}

// This prevents defeated monsters from appearing alive again
function roomDescription($room, $story) {
    if ($room == "courtyard" && $_SESSION["monster_defeated"]) {
        return "The courtyard is quiet. The cursed soldier lies on the ground. The forest is north and the castle gate is east.";
    }
    if ($room == "clearing" && $_SESSION["forest_monster_defeated"]) {
        return "The forest clearing is quiet. The dead monster lies near a tree. The village is north and the path is south.";
    }
    if ($room == "throne" && $_SESSION["king_defeated"]) {
        return "The monster king is dead. The castle hall is south and the crown room is north.";
    }
    return $story[$room]["description"];
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $command = strtolower(trim($_POST["command"] ?? ""));
    $room = $_SESSION["location"];
    $bag = $_SESSION["inventory"];

    if ($_SESSION["game_over"] || $_SESSION["game_finished"]) {
        $message = "The game has ended. Restart to play again.";
    }
    else if ($command == "help") {
        $message = "Commands: look around, take item, open door, force door, go north, go south, go east, go west, open bag, drop [item], use [item], fight, talk, stats, journal, help. At the end: destroy crown, take crown, leave crown.";
    }
    else if ($command == "look around") {
        $_SESSION["searched"][$room] = true;
        $item = $_SESSION["room_items"][$room];
        if ($item != "") {
            $message = "You search the area and find: " . $item . ".";
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
            $message = "You should look around the room first.";
        }
        else {
            $_SESSION["inventory"][] = $item;
            $_SESSION["room_items"][$room] = "";
            $message = "You picked up the " . $item . ".";
            if ($item == "silver sword") {
                $_SESSION["choices"][] = "You found the silver sword.";
            }
        }
    }
    else if ($command == "open bag") {
        if (count($bag) == 0) {
            $message = "Your bag is empty.";
        }
        else {
            $message = "Your bag contains: " . implode(", ", $bag);
        }
    }
    else if (substr($command, 0, 5) == "drop ") {
        $item = trim(substr($command, 5));
        if (!in_array($item, $bag)) {
            $message = "You do not have that item.";
        }
        else if ($_SESSION["room_items"][$room] != "") {
            $message = "There is already an item here.";
        }
        else {
            $position = array_search($item, $bag);
            unset($_SESSION["inventory"][$position]);
            $_SESSION["inventory"] = array_values($_SESSION["inventory"]);
            $_SESSION["room_items"][$room] = $item;
            $message = "You dropped the " . $item . ".";
        }
    }
    else if (substr($command, 0, 4) == "use ") {
        $item = trim(substr($command, 4));
        if (!in_array($item, $bag)) {
            $message = "You do not have that item.";
        }
        else if ($item == "old letter") {
            $message = "The letter says the king used dark magic before he disappeared.";
        }
        else if ($item == "king's journal") {
            $message = "The king's journal says the cursed crown turned him into a monster. A silver sword can kill him.";
        }
        else if ($item == "rusty key") {
            $message = "You have a rusty key. It probably opens a door.";
        }
        else if ($item == "old sword") {
            $message = "You hold your old sword. It will be useful in a fight.";
        }
        else if ($item == "silver sword") {
            $message = "You hold the silver sword. It can kill stronger monsters.";
        }
    }
    else if ($command == "force door") {
        if ($room != "cell") {
            $message = "There is no prison door to force here.";
        }
        else if ($_SESSION["door_open"]) {
            $message = "The door is already open.";
        }
        else if ($_SESSION["hero"]["strength"] >= 3) {
            $_SESSION["door_open"] = true;
            $_SESSION["choices"][] = "You escaped the prison by forcing the door.";
            $message = "You force the prison door open. You can now leave the cell.";
        }
        else {
            $message = "You are not strong enough. You need the key.";
        }
    }
    else if ($command == "open door") {
        if ($room != "cell") {
            $message = "There is no door to open here.";
        }
        else if ($_SESSION["door_open"]) {
            $message = "The door is already open.";
        }
        else if (in_array("rusty key", $bag)) {
            $_SESSION["door_open"] = true;
            $_SESSION["choices"][] = "You escaped the prison cell.";
            $message = "You've unlocked and opened the door.";
        }
        else {
            $message = "The door is locked. You need a key.";
        }
    }
    else if (substr($command, 0, 3) == "go ") {
        $direction = trim(substr($command, 3));
        $exits = $story[$room]["exits"];
        if (!isset($exits[$direction])) {
            $message = "You cannot go that way.";
        }
        else if ($room == "cell" && $direction == "north" && !$_SESSION["door_open"]) {
            $message = "The door is closed. You need to open it.";
        }
        else if ($room == "courtyard" && $direction == "north" && !$_SESSION["monster_defeated"]) {
            $message = "The cursed soldier blocks the exit. You need to defeat him first.";
        }
        else if ($room == "courtyard" && $direction == "east" && !$_SESSION["village_visited"]) {
            $message = "This gate is locked. You should explore the village first.";
        }
        else if ($room == "gate" && $direction == "north" && !$_SESSION["village_visited"]) {
            $message = "You should visit the village first.";
        }
        else if ($room == "throne" && $direction == "north" && !$_SESSION["king_defeated"]) {
            $message = "You must defeat the monster king to continue.";
        }
        else {
            $_SESSION["location"] = $exits[$direction];
            $message = "You move " . $direction . ".";
            if ($_SESSION["location"] == "village" && !$_SESSION["village_visited"]) {
                $_SESSION["village_visited"] = true;
                $_SESSION["choices"][] = "You reached the village.";
            }
        }
    }
    else if ($command == "fight") {
        $regularSword = in_array("old sword", $bag) || in_array("silver sword", $bag);
        $enemy = "";
        $xp = 20;
        $needsSilver = false;

        if ($room == "courtyard" && !$_SESSION["monster_defeated"]) {
            $enemy = "cursed soldier";
            $flag = "monster_defeated";
        }
        else if ($room == "clearing" && !$_SESSION["forest_monster_defeated"]) {
            $enemy = "forest monster";
            $flag = "forest_monster_defeated";
        }
        else if ($room == "throne" && !$_SESSION["king_defeated"]) {
            $enemy = "monster king";
            $flag = "king_defeated";
            $needsSilver = true;
            $xp = 50;
        }

        if ($enemy == "") {
            $message = "There is nothing to fight here.";
        }
        else if (($needsSilver && in_array("silver sword", $bag)) || (!$needsSilver && $regularSword)) {
            $_SESSION[$flag] = true;
            $_SESSION["xp"] += $xp;
            $_SESSION["choices"][] = "You defeated the " . $enemy . ".";
            $message = "You defeated the " . $enemy . ". You gained " . $xp . " XP.";
            if ($room == "courtyard") {
                $message .= " The path out of the castle is now open.";
            }
            else if ($room == "clearing") {
                $message .= " The forest is safer now.";
            }
            if ($room == "throne") {
                $message .= " The crown room is open to the north.";
            }
        }
        else {
            $_SESSION["health"] = 0;
            $_SESSION["game_over"] = true;
            $message = "You fight the " . $enemy . " without the right weapon. You died. Game Over. Your choice to fight ended your adventure.";
        }
    }
    else if ($command == "talk") {
        if ($room == "tavern") {
            $message = "The old soldier says the king used dark magic. He tells you to find a silver sword in the village chapel.";
            if (!in_array("You spoke to the old soldier.", $_SESSION["choices"])) {
                $_SESSION["choices"][] = "You spoke to the old soldier.";
            }
        }
        else {
            $message = "There is nobody here to talk to.";
        }
    }
    else if ($command == "stats") {
        $hero = $_SESSION["hero"];
        $message = "Name: " . $hero["name"] . " | Class: " . $hero["class"] .
            " | Strength: " . $hero["strength"] . " | Magic: " . $hero["magic"] .
            " | Speed: " . $hero["speed"] . " | Health: " . $_SESSION["health"] .
            " | XP: " . $_SESSION["xp"];
    }
    else if ($command == "journal") {
        $message = "Journal:\n";
        if (count($_SESSION["choices"]) == 0) {
            $message .= "You have just started your adventure.\n";
        }
        else {
            $message .= implode("\n", $_SESSION["choices"]) . "\n";
        }

        if ($room == "crown") {
            $message .= "Prediction: Destroy the crown to break the curse, take it to rule, or leave it behind.";
        }
        else if ($room == "throne" && !$_SESSION["king_defeated"]) {
            $message .= in_array("silver sword", $bag) ?
                "Prediction: The silver sword should kill the king." :
                "Prediction: Fighting the king without a silver sword will kill you.";
        }
        else if (($room == "courtyard" && !$_SESSION["monster_defeated"]) ||
                 ($room == "clearing" && !$_SESSION["forest_monster_defeated"])) {
            $message .= (in_array("old sword", $bag) || in_array("silver sword", $bag)) ?
                "Prediction: Your sword should defeat this monster." :
                "Prediction: Fighting without a sword will kill you.";
        }
        else if (!$_SESSION["village_visited"]) {
            $message .= "Next: Travel through the forest to the village.";
        }
        else if (!in_array("silver sword", $bag)) {
            $message .= "Next: Find the silver sword at the chapel.";
        }
        else {
            $message .= "Next: Return to the castle and find the king.";
        }
    }
    else if ($command == "destroy crown" || $command == "take crown" || $command == "leave crown") {
        if ($room != "crown" || !$_SESSION["king_defeated"]) {
            $message = "You cannot do that here.";
        }
        else {
            if ($command == "destroy crown") {
                $_SESSION["location"] = "ending_destroy";
                $_SESSION["choices"][] = "You destroyed the crown.";
            }
            else if ($command == "take crown") {
                $_SESSION["location"] = "ending_take";
                $_SESSION["choices"][] = "You took the crown.";
            }
            else {
                $_SESSION["location"] = "ending_leave";
                $_SESSION["choices"][] = "You left the crown behind.";
            }
            $_SESSION["game_finished"] = true;
            $message = "";
        }
    }
    else {
        $message = "Invalid command. Type help to see commands.";
    }

    // Save the command and game response to history
    $_SESSION["history"][] = "> " . $command;
    if ($message != "") {
        $_SESSION["history"][] = $message;
    }
    if ($_SESSION["location"] != $room) {
        $_SESSION["history"][] = roomDescription($_SESSION["location"], $story);
    }
    if ($_SESSION["game_finished"] && $_SESSION["location"] != $room) {
        $hero = $_SESSION["hero"];
        $summary = $hero["name"] . " the " . $hero["class"] . ". ";
        $summary .= implode(" ", $_SESSION["choices"]);
        $summary .= " " . $story[$_SESSION["location"]]["name"] . ".";
        $_SESSION["history"][] = "Your Story Summary: " . $summary;
        $_SESSION["history"][] = "Final XP: " . $_SESSION["xp"];
        // Add this completed adventure to the session leaderboard.
        if (!isset($_SESSION["leaderboard"])) {
            $_SESSION["leaderboard"] = array();
        }
        $_SESSION["leaderboard"][] = array(
            "player" => $_SESSION["username"],
            "character" => $hero["name"],
            "class" => $hero["class"],
            "xp" => $_SESSION["xp"],
            "ending" => $story[$_SESSION["location"]]["name"]
        );
    }
}

$room = $_SESSION["location"];
$currentStory = $story[$room];

// Simple Story Advisor: warn the player before important decisions
$preview = "Type journal for your story history and a hint.";
$bag = $_SESSION["inventory"];

if ($_SESSION["game_finished"]) {
    $preview = "Your choices have decided the kingdom's future.";
}
else if ($_SESSION["game_over"]) {
    $preview = "The wrong weapon led to the end of your adventure.";
}
else if ($room == "cell" && !$_SESSION["door_open"]) {
    if ($_SESSION["hero"]["strength"] >= 3) {
        $preview = "You are strong enough to force the door, or you can find the key.";
    }
    else {
        $preview = "You are not strong enough to force the door. Search for a key.";
    }
}
else if ($room == "courtyard" && !$_SESSION["monster_defeated"]) {
    $preview = in_array("old sword", $bag) || in_array("silver sword", $bag) ?
        "Your sword should defeat the cursed soldier." :
        "Fighting the soldier without a sword will end your adventure.";
}
else if ($room == "clearing" && !$_SESSION["forest_monster_defeated"]) {
    $preview = in_array("old sword", $bag) || in_array("silver sword", $bag) ?
        "You can fight the forest monster or continue toward the village." :
        "You can pass the monster, but fighting without a sword is deadly.";
}
else if ($room == "throne" && !$_SESSION["king_defeated"]) {
    $preview = in_array("silver sword", $bag) ?
        "The silver sword can defeat the monster king." :
        "You need the silver sword before you fight the monster king.";
}
else if ($room == "crown") {
    $preview = "Destroying the crown breaks the curse; taking it makes you ruler; leaving it keeps the curse.";
}
else if ($_SESSION["village_visited"] && !in_array("silver sword", $bag)) {
    $preview = "The village chapel may contain the weapon needed to defeat the king.";
}

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
        <a href="logout.php">Logout</a>
    </nav>
    <main>
        <h2>Current Location: <?php echo htmlspecialchars($currentStory["name"]); ?></h2>
        <p>Character: <?php echo htmlspecialchars($_SESSION["hero"]["name"]); ?> (<?php echo htmlspecialchars($_SESSION["hero"]["class"]); ?>)</p>
        <div class="story-preview">
            <strong>Story Advisor:</strong>
            <?php echo htmlspecialchars($preview); ?>
        </div>
        <div class="game-log">
            <?php foreach (array_reverse($_SESSION["history"]) as $entry) { ?>
                <p><?php echo nl2br(htmlspecialchars($entry)); ?></p>
            <?php } ?>
        </div>
        <?php if ($_SESSION["game_over"] || $_SESSION["game_finished"]) { ?>
            <h3 class="ending-title"><?php echo $_SESSION["game_finished"] ? "The End" : "Game Over"; ?></h3>
            <form action="game.php" method="post">
                <button type="submit" name="restart" value="1">Restart Game</button>
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
