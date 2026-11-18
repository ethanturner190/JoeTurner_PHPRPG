<?php
session_start();
include "story.php";

// clear game variables on restart, does not clear login variables
$gameKeys = array(
    "location", "inventory", "searched", "room_items", "door_open",
    "monster_defeated", "forest_monster_defeated", "king_defeated",
    "village_visited", "game_over", "game_finished", "health", "xp",
    "history", "choices"
);

// Restart game
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["restart"])) {
    foreach ($gameKeys as $key) {
        unset($_SESSION[$key]);
    }
    header("Location: game.php");
    exit;
}

// Set up new game, including when coming from our old four-room version
if (!isset($_SESSION["location"])) {

    foreach ($gameKeys as $key) {
        unset($_SESSION[$key]);
    }

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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $command = strtolower(trim($_POST["command"] ?? ""));
    $room = $_SESSION["location"];

    if ($_SESSION["game_over"] || $_SESSION["game_finished"]) {
        $message = "The game has ended. Restart to play again.";
    }

    else if ($command == "help") {
        $message = "Commands: look around, take item,
        open door, go north, go south, go east, go west,
        open bag, drop [item], use [item], fight,
        talk, stats, journal, help.
        At the end: destroy crown, take crown, leave crown.";
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
            $message = "You should look around the room first.";
        }
        else {
            $_SESSION["inventory"][] = $item;
            $_SESSION["room_items"][$room] = "";
            $message = "You picked up the " . $item . ".";

            if ($item == "silver sword") {
                $_SESSION["choices"][] = "You found the silver sword at the chapel.";
            }
        }
    }

    else if ($command == "open bag") {
        if (count($_SESSION["inventory"]) == 0) {
            $message = "Your bag is empty.";
        }
        else {
            $message = "Your bag contains: " . implode(", ", $_SESSION["inventory"]);
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
        else if ($item == "silver sword") {
            $message = "You hold the silver sword. It can kill stronger monsters.";
        }
        else if ($item == "old letter") {
            $message = "The letter says the king was using dark magic
            before he disappeared. Something happened inside the castle.";
        }
        else if ($item == "king's journal") {
            $message = "The king's journal says he used a cursed crown
            to gain power. The crown changed him into a monster.
            The silver sword should be able to kill him.";
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
                $_SESSION["choices"][] = "You escaped the prison cell.";
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
        else if ($room == "cell" && $direction == "north" &&
                 !$_SESSION["door_open"]) {
            $message = "The door is closed. You need to open it.";
        }
        else if ($room == "courtyard" && $direction == "north" &&
                 !$_SESSION["monster_defeated"]) {
            $message = "The cursed soldier is blocking the exit.
            You need to defeat him first.";
        }
        else if ($room == "courtyard" && $direction == "east" &&
                 !$_SESSION["village_visited"]) {
            $message = "This gate is locked from inside.
            You should explore beyond the forest first.";
        }
        else if ($room == "gate" && $direction == "north" &&
                 !$_SESSION["village_visited"]) {
            $message = "You should visit the village before entering the castle.";
        }
        else if ($room == "throne" && $direction == "north" &&
                 !$_SESSION["king_defeated"]) {
            $message = "The monster king blocks the way.
            You must defeat him to continue.";
        }
        else {
            $_SESSION["location"] = $exits[$direction];
            $message = "You move " . $direction . ".";

            if ($_SESSION["location"] == "village" && !$_SESSION["village_visited"]) {
                $_SESSION["village_visited"] = true;
                $_SESSION["choices"][] = "You reached the abandoned village.";
            }
        }
    }

    else if ($command == "fight") {
        // Regular monsters can be killed with either sword
        $hasWeapon = in_array("old sword", $_SESSION["inventory"]) ||
                     in_array("silver sword", $_SESSION["inventory"]);

        if ($room == "courtyard" && !$_SESSION["monster_defeated"]) {
            if ($hasWeapon) {
                $_SESSION["monster_defeated"] = true;
                $_SESSION["xp"] += 20;
                $message = "You draw your sword and fight the
                cursed soldier. You kill it. You gained 20 XP.";
                $_SESSION["choices"][] = "You defeated the cursed soldier.";
            }
            else {
                $_SESSION["health"] = 0;
                $_SESSION["game_over"] = true;
                $message = "You attack the cursed soldier without
                a weapon. Now you're dead. Game Over.";
            }
        }
        else if ($room == "clearing" && !$_SESSION["forest_monster_defeated"]) {
            if ($hasWeapon) {
                $_SESSION["forest_monster_defeated"] = true;
                $_SESSION["xp"] += 20;
                $message = "You fight the forest monster and kill it.
                You gained 20 XP.";
                $_SESSION["choices"][] = "You defeated the forest monster.";
            }
            else {
                $_SESSION["health"] = 0;
                $_SESSION["game_over"] = true;
                $message = "You try to fight the forest monster
                without a sword. It kills you. Game Over.";
            }
        }
        else if ($room == "throne" && !$_SESSION["king_defeated"]) {
            // The king is a stronger monster and needs the silver sword
            if (in_array("silver sword", $_SESSION["inventory"])) {
                $_SESSION["king_defeated"] = true;
                $_SESSION["xp"] += 50;
                $message = "You fight the monster king with the silver sword.
                After a long fight, you kill him. You gained 50 XP.
                The crown room is now open to the north.";
                $_SESSION["choices"][] = "You defeated the monster king.";
            }
            else {
                $_SESSION["health"] = 0;
                $_SESSION["game_over"] = true;
                $message = "Your weapon cannot kill the monster king.
                He kills you. Game Over.";
            }
        }
        else {
            $message = "There is nothing to fight here.";
        }
    }

    else if ($command == "talk") {
        if ($room == "tavern") {
            $message = "The old soldier says the king was using
            dark magic before the curse started. He tells you
            to look in the village chapel for a silver sword.";
            if (!in_array("You spoke to the old soldier.", $_SESSION["choices"])) {
                $_SESSION["choices"][] = "You spoke to the old soldier.";
            }
        }
        else {
            $message = "There is nobody here to talk to.";
        }
    }

    else if ($command == "stats") {
        $message = "Health: " . $_SESSION["health"] . " | XP: " . $_SESSION["xp"];
    }

    else if ($command == "journal") {
        $message = "Journal:\n";
        if (count($_SESSION["choices"]) == 0) {
            $message .= "You have just started your adventure.\n";
        }
        else {
            foreach ($_SESSION["choices"] as $choice) {
                $message .= "- " . $choice . "\n";
            }
        }

        // Simple consequence predictions from player items and location
        if ($room == "courtyard" && !$_SESSION["monster_defeated"]) {
            if (in_array("old sword", $_SESSION["inventory"]) ||
                in_array("silver sword", $_SESSION["inventory"])) {
                $message .= "Prediction: You have a sword. Fighting the soldier should work.";
            }
            else {
                $message .= "Prediction: Fighting without a sword will get you killed.";
            }
        }
        else if ($room == "clearing" && !$_SESSION["forest_monster_defeated"]) {
            if (in_array("old sword", $_SESSION["inventory"]) ||
                in_array("silver sword", $_SESSION["inventory"])) {
                $message .= "Prediction: You can fight this monster with a sword, or leave.";
            }
            else {
                $message .= "Prediction: You can avoid the monster. Fighting without a sword is dangerous.";
            }
        }
        else if ($room == "throne" && !$_SESSION["king_defeated"]) {
            if (in_array("silver sword", $_SESSION["inventory"])) {
                $message .= "Prediction: The silver sword should work against the king.";
            }
            else {
                $message .= "Prediction: The king is too strong for a regular sword.";
            }
        }
        else if ($room == "crown") {
            $message .= "Prediction: Destroy the crown to break the curse, take it to rule,
            or leave it and the curse will remain.";
        }
        else if (!$_SESSION["village_visited"]) {
            $message .= "Next: Leave the castle and explore the village.";
        }
        else if (!in_array("silver sword", $_SESSION["inventory"])) {
            $message .= "Next: Find the silver sword in the village chapel.";
        }
        else {
            $message .= "Next: Return to the castle and find the king.";
        }
    }

    else if ($command == "destroy crown" ||
             $command == "take crown" ||
             $command == "leave crown") {
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
            $message = "Your choice decides how the story ends.";
        }
    }

    else {
        $message = "Invalid command. Type help to see commands.";
    }

    // Keep the typed command and response in the scrolling history
    $_SESSION["history"][] = "> " . $command;
    if ($message != "") {
        $_SESSION["history"][] = $message;
    }

    // description for new room
    if ($_SESSION["location"] != $room) {
        $newRoom = $_SESSION["location"];

        if ($newRoom == "courtyard" && $_SESSION["monster_defeated"]) {
            $_SESSION["history"][] = "The courtyard is quiet.
            The cursed soldier you killed lies on the ground.
            The forest is north and the castle gate is east.";
        }
        else if ($newRoom == "clearing" && $_SESSION["forest_monster_defeated"]) {
            $_SESSION["history"][] = "The forest clearing is quiet.
            The monster you killed lies near the fallen tree.
            The village is north and the forest path is south.";
        }
        else if ($newRoom == "throne" && $_SESSION["king_defeated"]) {
            $_SESSION["history"][] = "The monster king is dead.
            The castle hall is south and the crown room is north.";
        }
        else {
            $_SESSION["history"][] = $story[$newRoom]["description"];
        }
    }

    // Show an ending summary after the ending description
    if ($_SESSION["game_finished"] && $_SESSION["location"] != $room) {
        $_SESSION["history"][] = "Your Story Summary: " . implode(" ", $_SESSION["choices"]);
        $_SESSION["history"][] = "Final XP: " . $_SESSION["xp"];
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
                <p><?php echo nl2br(htmlspecialchars($entry)); ?></p>
            <?php } ?>
        </div>

        <?php if ($_SESSION["game_over"] || $_SESSION["game_finished"]) { ?>
            <h3><?php echo $_SESSION["game_finished"] ? "The End" : "Game Over"; ?></h3>
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
