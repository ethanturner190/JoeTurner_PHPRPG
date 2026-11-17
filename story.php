
<?php

$story = array(
    "cell" => array(
        "name" => "Prison Cell",
        "description" => "You wake up inside a cold prison cell.
        You cannot remember how you got here. There are loose
        stones in the wall and a wooden door to the north.",
        "item" => "rusty key",
        "exits" => array("north" => "hallway")
    ),

    "hallway" => array(
        "name" => "Dungeon Hallway",
        "description" => "You enter a dark hallway with broken
        chains hanging from the walls. Your cell is south.
        There is a guard room to the east and a staircase
        leading north.",
        "item" => "",
        "exits" => array(
            "south" => "cell",
            "east" => "guardroom",
            "north" => "courtyard"
        )
    ),

    "guardroom" => array(
        "name" => "Guard Room",
        "description" => "You enter an old guard room.
        There is a broken table and some old armor.
        A weapon rack stands against the wall.
        The hallway is to the west.",
        "item" => "old sword",
        "exits" => array("west" => "hallway")
    ),

    "courtyard" => array(
        "name" => "Castle Courtyard",
        "description" => "You walk into an abandoned courtyard.
        A cursed soldier stands near the castle gate.
        His body is disfigured and his armor is covered
        in rust. You can return south to the dungeon.",
        "item" => "",
        "exits" => array("south" => "hallway")
    )
);

?>
