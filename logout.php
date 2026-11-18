<?php
session_start();
// Log out, but keep the temporary registration for logging back in.
unset($_SESSION["username"]);
unset($_SESSION["hero"]);
$gameKeys = array("location", "inventory", "searched", "room_items", "door_open",
    "monster_defeated", "forest_monster_defeated", "king_defeated",
    "village_visited", "game_over", "game_finished", "health", "xp",
    "history", "choices");
foreach ($gameKeys as $key) {
    unset($_SESSION[$key]);
}
header("Location: index.php");
exit;
?>
