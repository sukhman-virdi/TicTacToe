=======================================================================
# TICTACTOE GAME DATABASE & WEBSITE
=======================================================================
Created for: Langara College - CPSC 2221 - Final Project
Created by: Alberto Urquidi, Alessandro Verga, Tomoyoshi Sakai, Sukhmanpreet Singh

=====================================================
## PROJECT DESCRIPTION
=====================================================
The TicTacToe Platform is a web application built using PHP, MySQL, HTML, CSS, and JavaScript. 
Players can play TicTacToe against an AI opponent, track their stats, view a live leaderboard, 
replay past matches move by move, and join tournaments. Administrators can manage achievements and oversee users.
Tournament Managers can create and manage tournaments.


=====================================================
## CORE FEATURES
=====================================================

Player authentication - sign up, log in, and session-based access control with role detection (Player, Manager, Admin)
TicTacToe game vs AI - matches automatically saved to the database including all moves, result, and updated player stats
Player profile - displays wins, losses, draws, ranking points, recent match history, and achievements
Leaderboard - top 10 players by ranking points with current user highlighted even if outside top 10, plus nested aggregation results
Tournament system - managers create tournaments, players join them through the Join Tournament page
Achievement system - admins add, edit, and delete achievements; players earn them through gameplay
Admin panel - oversee users with comment system, edit admin levels with projection query, division query for fully-reviewed users
Real-time availability checking - username and email uniqueness verified instantly during sign up

The database schema follows 3NF normalization across 15 tables. All queries use prepared statements.


=====================================================
## SETUP INSTRUCTIONS
=====================================================

Step 1 - Install XAMPP
Download XAMPP
Install XAMPP with default settings
Launch the XAMPP Control Panel
Start both the Apache and MySQL modules

----------------------------------------------- 

Step 2 - Copy Project Files
Navigate to C:\xampp\htdocs
Create a new folder named tictactoe
Copy all submitted project files into the tictactoe folder (all files flat, no subfolders other than /assets)

----------------------------------------------- 

Step 3 - Set Up the Database
Open Command Prompt and navigate to the MySQL bin folder:
cd C:\xampp\mysql\bin
Run the setup script:
mysql -u root -p tictactoe < "C:\xampp\htdocs\tictactoe\create_tables.sql"
Enter your xampp admin password. Press Enter when prompted for no password (default XAMPP has no password)

----------------------------------------------- 

Step 4 - Run the Application
Open your browser and go to: http://localhost/tictactoe/home.html
Click Sign Up to create a player account, or use Login > Staff Login for admin/manager access

----------------------------------------------- 

Troubleshooting
Connection error — make sure MySQL is running in the XAMPP Control Panel
Access denied — press Enter for an empty password at the CMD prompt
Pages not found — confirm the folder is named tictactoe inside htdocs
