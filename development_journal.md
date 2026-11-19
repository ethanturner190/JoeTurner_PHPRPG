PHPRPG Development Journal
Project: PHPRPG - The Cursed Kingdom
Class: CSC 4370 - Project 2 (Topic 05: Choose Your Path)
Team: RPG Boys - Joe Turner and Shozab Mirza
Date: November 18, 2026

Live website: https://codd.cs.gsu.edu/~jturner178/WEB/PW/PW2/index.php
GitHub: https://github.com/ethanturner190/JoeTurner_PHPRPG
Trello: https://trello.com/b/g3TiKmmN/phprpg
YouTube URL: https://youtu.be/mgPlBsm1O2g

Project Overview
Our project is a text-based RPG made with PHP, HTML, and CSS. The player creates a character, escapes a prison cell, explores the castle, forest, and village, and eventually finds the missing king. The player can collect items, fight monsters, earn XP, and choose one of three endings. We used PHP sessions to keep track of the game while the player is using the website.

Team Roles
- Joe: Scrum Master and PHP lead. Worked on the story, commands, inventory, fighting, endings, debugging, and final integration.
- Shozab: Project Owner and development journal lead. Worked on the homepage, CSS, account pages, character creation, leaderboard, and documentation.
- Both: Helped with planning, testing, and making changes to the website.

Development Sprints

Sprint 1 - Planning 
We chose the Choose Your Path RPG topic and planned a medieval story about a cursed kingdom. We decided to include character classes, an inventory, monsters, and three endings. We also planned our work using Trello and discussed team responsibilities.

Sprint 2 - Starting the Game
We uploaded the first version of the homepage, stylesheet, game.php, and story.php to GitHub. We used PHP arrays for the rooms, items, and directions. The basic commands allowed the player to move around and collect items. We used $_SESSION so the game could remember the player's location and inventory.

Sprint 3 - Character Creation and Game Features
We added more rooms, fighting, health, XP, and the three character classes: Warrior, Mage, and Rogue. Each class has different stats. The Warrior can force the prison door open, while the other classes need to find the key. We also made sure items do not return after being collected and monsters stay defeated.

Sprint 4 - Story, Journal, and Endings
We finished the castle, forest, and village areas. The player needs the silver sword to defeat the monster king. At the end, the player can destroy, take, or leave the crown. We added the journal and a rule-based Story Advisor that gives hints depending on the player's location and inventory. We also added a summary of the player's important choices.

Sprint 5 - Login and Leaderboard 
We added registration, login, logout, and auth.php to protect the game pages. We used password_hash() and password_verify() for the demo account. We decided to store accounts in PHP sessions instead of a server file because we wanted to avoid Codd permission problems. We added a leaderboard showing the player, character, class, XP, and ending, sorted by highest XP.
We also added links for Game, Character Creation, and Leaderboard so the pages would be easier to use.

Sprint 6 - Final Changes and Testing 
We tested the commands and completed the story. Some ending messages repeated themselves, so we removed the extra text. We fixed item messages like a old sword and worked on keeping recent commands visible in the history box. We also added CSS animations to the Story Advisor and made The End appear in larger gold text with a slower animation. We updated the README and checked the site for final problems.

Testing
- Character creation testing: 9 out of 9 passed.
- Leaderboard testing: 5 out of 5 passed.
- We completed a full Warrior playthrough and earned 90 XP by defeating all three monsters.
- We checked the PHP files for syntax errors.
- We tested registration, login, logout, and starting the game on Codd.

Changes from proposal
We originally wanted persistent accounts and saved progress across logins. The finished version instead uses PHP sessions, so a player's progress lasts during the active session. We also decided to rank leaderboard scores by XP instead of tracking the time taken.