PHPRPG - The Cursed Kingdom
CSC 4370 - Project 2
Topic 05: Choose Your Path (Text RPG)
Team: Joe Turner and Shozab Mirza

About
PHPRPG is a text based adventure game. The game is created using php, html, and css. First you must create an account, then make a character then you can start the game.
You wake up in a prison cell and must escape. There are a set list of commands to control the character. After entering a valid command the stroy will progress and tell you about your surroundings. There will be an advisor that can give you tips when you hit certain areas. Search rooms, find loot, fight monsters and findout what happened to the kingdom.

To see a list of commands type "help"

How to run
Everything is uploaded to CODD. Either go to the URL or upload all files to a server that can run php. Create an account. Create a character. Start the game. The game will end after the story is over. You can restart the game at the end or when you die. 
CODD URL: https://codd.cs.gsu.edu/~jturner178/WEB/PW/PW2/index.php
GitHub URL: https://github.com/ethanturner190/JoeTurner_PHPRPG
YouTube URL: https://youtu.be/mgPlBsm1O2g

AI Advisor
The advisor uses rules from the php. It sees where you are and what your inventory holds to give you tips on where to go and if you can deafeat the enemy near you. 
There is also a journal that keeps up with the commands you entered, main story points and will give you a summary of what you've done along with your stats. 

Testing
Everything was tested using the CODD server as a host. We have gone through the entire story many times, entering every room, picking up every item, not picking up items, all story ending. The game makes you login into an account before you play. If you don't have an account you make one first. If you make it part way through the game then make a new character, the game restarts as intended. Leadboard shows each completed game. We had a couple friends play the game. They asked for help a few times because they've never played a text based game before but they did not find any exploits or ways to break the game. 

Limitation
Acounts are session based and do not persist on the server. 
Leaderboard is session based and does not persist on the server.
You can switch between pages but if you logout you must create a new character which restarts the game. 
You must enter commands exactly as intended.
The warrior class is the only one that gives a meaniful difference

Development
Joe Turner: Game logic and implementation of story. Partner did help with story points and progression ideas. Implemented commands, inventory. 

Shozab Mirza: Created the homepage CSS styling, leaderboard system, login and registration system, most documentation. 

We both did testing for the site, gave input on design features, debugging.

List of commands
| Command | Purpose |
| --- | --- |
| `help` | Show available commands |
| `look around` | Search the current room |
| `take item` | Pick up the item |
| `open bag` | View inventory |
| `use "item name"` | Equips item (replace old item equipped) |
| `drop "item name"` | Drop an item from inventory |
| `go north`, `go south`, `go east`, `go west` | Travel directions |
| `open door` | Opens unlocked doors and locked doors if you have the key |
| `force door` | Warrior can bash some door open |
| `fight` | Fight current enemy |
| `talk` | Will talk to person if they are there |
| `stats` | Show character stats, health, and XP |
| `journal` | Show your choices and pridictions |
| `destroy crown`, `take crown`, `leave crown` | Different ending choices |

Features
Forms: POST used for commands, registration, login and character creation.
Sessions: Session data used for login, your character, current room, health, inventory, enemy states, xp, journal and leaderboard.
16 different locations using arrays.
Character classes: Warrior, Mage, Rouge. 
Inventory: Character can pick up, drop and store items.
Combat: Simple combat system. Basically if weapon is good enough, then monster dies
Three seperate endings
Leaderboard: Completed games get added to leaderboard. Highest xp goes on top.
AI advisor: View current room and inventory to predict outcomes and give tips.
Journal: Records bigish moments in the story. Will give a summary of what happened.
CSS effects: Small effects on some text

Files
| File | What it does |
| --- | --- |
| `README`| You're looking at it. Explains the game|
| `index.php` | Homepage |
| `register.php` | Create a temporary session account |
| `login.php` | Login to accout (must be in session) |
| `logout.php` | Logs out of account |
| `auth.php` | Redirects user to correct page |
| `character.php` | Character name, class, and stats |
| `game.php` | Main game commands, session state, story advisor, endings|
| `story.php` | Room descriptions, story progression, items, endings |
| `leaderboard.php` | Completed games and xp show up here |
| `styles.css` | Page design, CSS animations |
| `images/swamp-castle-background.jpg` | Site background image |

CODD URL: https://codd.cs.gsu.edu/~jturner178/WEB/PW/PW2/index.php
GitHub URL: https://github.com/ethanturner190/JoeTurner_PHPRPG
Sprint planner via Trello URL: https://trello.com/invite/b/6af0c7861f9286d5606d1662/ATTI691eb8c9397b86c98432984f2a8fbe8aFDE14ABD/phprpg