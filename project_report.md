Prject Report
Project: PHPRPG - The Cursed Kingdom
Class: CSC 4370 - Project 2
Team: Joe Turner and Shozab Mirza

Features
This project is a text based RPG game. We used php, html and css. The player starts in a room then must explore the kingdom, search their area, pick up loot, fight monsters and find the king. 
There are three classes to choose from. Each class has different stats assigned to them. The game has three main areas. The starting area within the cast, the outside forest that leads to a village, then the interior area of the castle. The game includes and inventory system, xp, health, three different endings. 
PHP sessions are used to keep track of progression, registration and login, the leaderboard. There is a journal that remembers commands and import story points.
The AI advisor gives you tips based on your current location and what is in your inventory. There are a couple of css animations to fit the assignment requirements. 

Challenges
An issue we had was showing the game history. After entering a command we want the player to see the newest message smoothly but we ran into issues where the histroy box would jump around or show the history logs in reverse order. 

At first we wanted persistant login data saved to the server but we kept running into permission issues with CODD even after following the CODD troubleshooting steps from a different assignment.

A routine issue we faced was the story progression logic. At first monsters would come back alive if you revisted an area that they were in. The same thing occured with items in a room. If you picked up the item, left the room then came back, the item would be there again. 

A challenge we never solved was we had to replay the entire game to get to late parts of the story very many times to test the game. Im sure there is a way to add a shortcut then delete it later for the full game but we never implemented that idea. 

Solutions
To fix the history issue I used a PHP session array. This stores the commands and messages. I then used css to dictate how the message would be displayed. After some trial an error I was able to get the newest message to show up at the bottom of the history window in a smooth fasion. 

For the login system we switched to using PHP session instead of saving the account data to the server. This allowed us to get around the permission issues with CODD when it comes to creating a new file or writing to a file. This does have the drawback of not actually saving data to a server for persistance. All data should be saved during a session. 

We also used session to keep track of the monster's state. If the monster is killed the session marks down  that it is dead so you wont run into again when returning to the area. We did the same thing for lootable items. 


This project helped us better understand PHP sessions, using arrays, using forms and implementing if statements inside php. It showed us that we had to keep track of item and monster status instead of displaying them blindly when entering the room. 