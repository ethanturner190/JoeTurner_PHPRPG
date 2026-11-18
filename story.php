<?php

$story = array(
    // Act 1: Escape the castle
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
        "exits" => array("south" => "cell", "east" => "guardroom", "north" => "courtyard")
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
        in rust. You can return south to the dungeon.
        The forest is north and another castle gate is east.",
        "item" => "",
        "exits" => array("south" => "hallway", "north" => "forestentrance", "east" => "gate")
    ),

    // Act 2: Forest and village
    "forestentrance" => array(
        "name" => "Forest Entrance",
        "description" => "You leave the castle and reach the forest.
        The trees block most of the sunlight. The castle
        courtyard is south and a path continues north.",
        "item" => "",
        "exits" => array("south" => "courtyard", "north" => "forestpath")
    ),

    "forestpath" => array(
        "name" => "Forest Path",
        "description" => "You follow a narrow path through the trees.
        You hear animals moving nearby. The forest entrance
        is south and a clearing is north.",
        "item" => "",
        "exits" => array("south" => "forestentrance", "north" => "clearing")
    ),

    "clearing" => array(
        "name" => "Forest Clearing",
        "description" => "You reach an open area in the forest.
        A wild monster is standing near a fallen tree.
        It hasn't attacked you. The forest path is south
        and the village is north.",
        "item" => "",
        "exits" => array("south" => "forestpath", "north" => "village")
    ),

    "village" => array(
        "name" => "Village Square",
        "description" => "You enter an abandoned village.
        Buildings are damaged and the streets are empty.
        There is a house east, a tavern west and a chapel north.
        The forest is south.",
        "item" => "",
        "exits" => array("south" => "clearing", "east" => "house", "west" => "tavern", "north" => "chapel")
    ),

    "house" => array(
        "name" => "Abandoned House",
        "description" => "You enter a small house. The furniture
        is knocked over and dishes cover the floor.
        Someone may have left something behind.
        The village square is west.",
        "item" => "old letter",
        "exits" => array("west" => "village")
    ),

    "tavern" => array(
        "name" => "Old Tavern",
        "description" => "You enter an old tavern. Most of the
        tables are empty. An old soldier sits near the fireplace.
        The village square is east.",
        "item" => "",
        "exits" => array("east" => "village")
    ),

    "chapel" => array(
        "name" => "Village Chapel",
        "description" => "You enter a small chapel. The windows
        are broken and the room is covered in dust.
        There is an old chest near the altar.
        The village square is south.",
        "item" => "silver sword",
        "exits" => array("south" => "village")
    ),

    // Act 3: Return to castle
    "gate" => array(
        "name" => "Castle Gate",
        "description" => "You reach another entrance to the castle.
        This part of the castle was closed off during the curse.
        The courtyard is west and the main hall is north.",
        "item" => "",
        "exits" => array("west" => "courtyard", "north" => "hall")
    ),

    "hall" => array(
        "name" => "Castle Hall",
        "description" => "You enter the main castle hall.
        Everything is covered in dust. The library is east,
        the throne room is north and the gate is south.",
        "item" => "",
        "exits" => array("south" => "gate", "east" => "library", "north" => "throne")
    ),

    "library" => array(
        "name" => "Castle Library",
        "description" => "You enter the castle library.
        Most of the books have fallen off their shelves.
        A desk stands in the middle of the room.
        The castle hall is west.",
        "item" => "king's journal",
        "exits" => array("west" => "hall")
    ),

    "throne" => array(
        "name" => "Throne Room",
        "description" => "You enter the throne room and find
        the missing king. The curse has turned him into
        a large monster. He stands between you and the crown room.
        The castle hall is south.",
        "item" => "",
        "exits" => array("south" => "hall", "north" => "crown")
    ),

    "crown" => array(
        "name" => "Crown Room",
        "description" => "The monster king is dead.
        His crown sits on a stone table. You have to decide
        what to do with it. Type destroy crown, take crown,
        or leave crown.",
        "item" => "",
        "exits" => array("south" => "throne")
    ),

    // Three separate story endings
    "ending_destroy" => array(
        "name" => "Ending: Curse Broken",
        "description" => "You destroy the crown. The dark magic
        starts to fade and the people of the kingdom can
        begin rebuilding. You defeated the king and ended the curse.",
        "item" => "",
        "exits" => array()
    ),

    "ending_take" => array(
        "name" => "Ending: New King",
        "description" => "You put on the crown and feel its
        dark magic taking control. You become the next
        cursed ruler of the kingdom.",
        "item" => "",
        "exits" => array()
    ),

    "ending_leave" => array(
        "name" => "Ending: Leave the Kingdom",
        "description" => "You leave the crown on the table
        and walk away. The monster king is dead, but
        the curse remains in the kingdom.",
        "item" => "",
        "exits" => array()
    )
);

?>
