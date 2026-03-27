-- ------------------------------------------------------------
-- TicTacToe Platform Database Setup Script
-- ------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS tictactoe;
USE tictactoe;

-- ------------------------------------------------------------
-- DROP TABLES
-- ------------------------------------------------------------

DROP TABLE IF EXISTS Joins;
DROP TABLE IF EXISTS Oversees;
DROP TABLE IF EXISTS Edits;
DROP TABLE IF EXISTS Awarded;
DROP TABLE IF EXISTS Plays_inMatch;
DROP TABLE IF EXISTS Move_MadeIn;
DROP TABLE IF EXISTS Match_Contained;
DROP TABLE IF EXISTS Tournament_Managed;
DROP TABLE IF EXISTS PrizeMoney;
DROP TABLE IF EXISTS Achievement;
DROP TABLE IF EXISTS TournamentManager;
DROP TABLE IF EXISTS Player;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS GameUser;
DROP TABLE IF EXISTS Department;

-- ------------------------------------------------------------
-- CREATE TABLES
-- ------------------------------------------------------------

CREATE TABLE Department (
    Level INT PRIMARY KEY,
    Department VARCHAR(40)
);
 
CREATE TABLE GameUser (
    UserID INT PRIMARY KEY,
    Username VARCHAR(40) UNIQUE NOT NULL,
    Email VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(40) NOT NULL
);
 
CREATE TABLE Admin (
    AdminID INT PRIMARY KEY,
    Name VARCHAR(40),
    Email VARCHAR(40) UNIQUE NOT NULL,
    Password VARCHAR(30) NOT NULL,
    Level INT,
    FOREIGN KEY (Level) REFERENCES Department(Level)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);
 
CREATE TABLE Player (
    PlayerID INT PRIMARY KEY,
    TotalWins INT DEFAULT 0,
    TotalLosses INT DEFAULT 0,
    TotalDraws INT DEFAULT 0,
    RankingPoints INT DEFAULT 0,
    FOREIGN KEY (PlayerID) REFERENCES GameUser(UserID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 
CREATE TABLE TournamentManager (
    ManagerID INT PRIMARY KEY,
    Tournaments_Organized INT DEFAULT 0,
    FOREIGN KEY (ManagerID) REFERENCES GameUser(UserID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 
CREATE TABLE Achievement (
    AchievementID INT PRIMARY KEY,
    Title VARCHAR(40) UNIQUE NOT NULL,
    Description VARCHAR(800)
);
 
CREATE TABLE PrizeMoney (
    Difficulty INT PRIMARY KEY,
    PrizeMoney INT DEFAULT 0
);
 
CREATE TABLE Tournament_Managed (
    TournamentID INT PRIMARY KEY,
    Name VARCHAR(50) UNIQUE NOT NULL,
    Difficulty INT,
    StartDate DATE,
    ManagerID INT NOT NULL,
    SupervisorID INT NOT NULL,
    FOREIGN KEY (Difficulty) REFERENCES PrizeMoney(Difficulty)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (SupervisorID) REFERENCES Admin(AdminID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (ManagerID) REFERENCES TournamentManager(ManagerID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 
CREATE TABLE Match_Contained (
    MatchID INT PRIMARY KEY,
    Match_Date DATE DEFAULT (CURRENT_DATE),
    Match_Time TIME DEFAULT (CURRENT_TIME),
    Winner_ID INT,
    TournamentID INT, -- can be null to save mactches with AI
    FOREIGN KEY (TournamentID) REFERENCES Tournament_Managed(TournamentID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (Winner_ID) REFERENCES Player(PlayerID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 
CREATE TABLE Move_MadeIn (
    MatchID INT,
    Move_Number INT,
    Position VARCHAR(20),
    PRIMARY KEY (MatchID, Move_Number),
    FOREIGN KEY (MatchID) REFERENCES Match_Contained(MatchID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 
CREATE TABLE Plays_inMatch (
    MatchID INT PRIMARY KEY,
    Player1_ID INT NOT NULL,
    Player2_ID INT NOT NULL,
    FOREIGN KEY (Player1_ID) REFERENCES Player(PlayerID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (Player2_ID) REFERENCES Player(PlayerID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 
CREATE TABLE Awarded (
    UserID INT,
    AchievementID INT,
    AwardDate DATE DEFAULT (CURRENT_DATE),
    PRIMARY KEY (UserID, AchievementID),
    FOREIGN KEY (UserID) REFERENCES GameUser(UserID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (AchievementID) REFERENCES Achievement(AchievementID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 
CREATE TABLE Edits (
    AdminID INT,
    AchievementID INT,
    EditDate DATE DEFAULT (CURRENT_DATE),
    PRIMARY KEY (AdminID, AchievementID),
    FOREIGN KEY (AdminID) REFERENCES Admin(AdminID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (AchievementID) REFERENCES Achievement(AchievementID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 
CREATE TABLE Oversees (
    AdminID INT,
    UserID INT,
    Admin_Comments VARCHAR(255),
    PRIMARY KEY (AdminID, UserID),
    FOREIGN KEY (AdminID) REFERENCES Admin(AdminID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (UserID) REFERENCES GameUser(UserID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
 
CREATE TABLE Joins (
    PlayerID INT,
    TournamentID INT,
    JoinDate DATE DEFAULT (CURRENT_DATE),
    PRIMARY KEY (PlayerID, TournamentID),
    FOREIGN KEY (PlayerID) REFERENCES Player(PlayerID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (TournamentID) REFERENCES Tournament_Managed(TournamentID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- ------------------------------------------------------------
-- INSERT DATA
-- ------------------------------------------------------------

INSERT INTO Department VALUES (1, 'Operations');
INSERT INTO Department VALUES (2, 'Management');
INSERT INTO Department VALUES (3, 'Moderation');
INSERT INTO Department VALUES (4, 'Support');
INSERT INTO Department VALUES (5, 'Development');
 
INSERT INTO GameUser VALUES (1, 'alex99', 'alex99@email.com', 'pass1234');
INSERT INTO GameUser VALUES (2, 'sarah_j', 'sarah@email.com', 'pass5678');
INSERT INTO GameUser VALUES (3, 'mikegamer', 'mike@email.com', 'pass9012');
INSERT INTO GameUser VALUES (4, 'lisa_play', 'lisa@email.com', 'pass3456');
INSERT INTO GameUser VALUES (5, 'john_doe', 'john@email.com', 'pass7890');
INSERT INTO GameUser VALUES (6, 'emma_win', 'emma@email.com', 'pass1111');
INSERT INTO GameUser VALUES (7, 'admin_tom', 'tom_user@email.com', 'pass2222');
INSERT INTO GameUser VALUES (8, 'admin_sue', 'sue_user@email.com', 'pass3333');
INSERT INTO GameUser VALUES (9, 'manager_bob', 'bob@email.com', 'pass4444');
INSERT INTO GameUser VALUES (10, 'manager_ann', 'ann@email.com', 'pass5555');
INSERT INTO GameUser VALUES (11, 'manager_carl', 'carl@email.com', 'pass6666');
INSERT INTO GameUser VALUES (12, 'manager_diana', 'diana@email.com', 'pass7777');
INSERT INTO GameUser VALUES (13, 'manager_evan', 'evan@email.com', 'pass8888');
INSERT INTO GameUser VALUES (14, 'dragon_x',     'dragon@email.com',     'pass1001');
INSERT INTO GameUser VALUES (15, 'shadow_wolf',   'shadow@email.com',     'pass1002');
INSERT INTO GameUser VALUES (16, 'thunder_bolt',  'thunder@email.com',    'pass1003');
INSERT INTO GameUser VALUES (17, 'ice_queen',     'icequeen@email.com',   'pass1004');
INSERT INTO GameUser VALUES (18, 'fire_storm',    'firestorm@email.com',  'pass1005');
INSERT INTO GameUser VALUES (19, 'ninja_star',    'ninja@email.com',      'pass1006');
INSERT INTO GameUser VALUES (20, 'cosmic_ray',    'cosmic@email.com',     'pass1007');
INSERT INTO GameUser VALUES (21, 'steel_fist',    'steel@email.com',      'pass1008');
INSERT INTO GameUser VALUES (22, 'silver_fox',    'silver@email.com',     'pass1009');
INSERT INTO GameUser VALUES (23, 'dark_knight',   'darkknight@email.com', 'pass1010');
INSERT INTO GameUser VALUES (24, 'blaze_runner',  'blaze@email.com',      'pass1011');
INSERT INTO GameUser VALUES (25, 'storm_chaser',  'storm@email.com',      'pass1012');
INSERT INTO GameUser VALUES (26, 'iron_eagle',    'ironeagle@email.com',  'pass1013');
INSERT INTO GameUser VALUES (27, 'quick_strike',  'quick@email.com',      'pass1014');
INSERT INTO GameUser VALUES (28, 'night_owl',     'nightowl@email.com',   'pass1015');
INSERT INTO GameUser VALUES (29, 'phantom_ace',   'phantom@email.com',    'pass1016');
INSERT INTO GameUser VALUES (30, 'wild_card',     'wildcard@email.com',   'pass1017');
INSERT INTO GameUser VALUES (31, 'golden_tiger',  'golden@email.com',     'pass1018');
INSERT INTO GameUser VALUES (32, 'red_viper',     'redviper@email.com',   'pass1019');
INSERT INTO GameUser VALUES (33, 'blue_falcon',   'bluefalcon@email.com', 'pass1020');
INSERT INTO GameUser VALUES (34, 'cyber_hawk',    'cyberhawk@email.com',  'pass1021');
INSERT INTO GameUser VALUES (35, 'frost_byte',    'frost@email.com',      'pass1022');
INSERT INTO GameUser VALUES (36, 'turbo_ace',     'turbo@email.com',      'pass1023');
INSERT INTO GameUser VALUES (37, 'nova_blast',    'nova@email.com',       'pass1024');
INSERT INTO GameUser VALUES (38, 'venom_strike',  'venom@email.com',      'pass1025');
INSERT INTO GameUser VALUES (39, 'laser_beam',    'laser@email.com',      'pass1026');
INSERT INTO GameUser VALUES (40, 'apex_predator', 'apex@email.com',       'pass1027');
INSERT INTO GameUser VALUES (41, 'omega_force',   'omega@email.com',      'pass1028');
INSERT INTO GameUser VALUES (42, 'pixel_rush',    'pixel@email.com',      'pass1029');
INSERT INTO GameUser VALUES (43, 'matrix_pro',    'matrix@email.com',     'pass1030');
INSERT INTO GameUser VALUES (44, 'manager_kate',  'kate@email.com',       'pass2001');
INSERT INTO GameUser VALUES (45, 'manager_liam',  'liam@email.com',       'pass2002');
INSERT INTO GameUser VALUES (46, 'manager_maya',  'maya@email.com',       'pass2003');
INSERT INTO GameUser VALUES (47, 'manager_noah',  'noah@email.com',       'pass2004');
INSERT INTO GameUser VALUES (48, 'manager_zoe',   'zoe@email.com',        'pass2005');
 
INSERT INTO Admin VALUES (1, 'Tom Harris', 'tom@email.com', 'pass2222', 2);
INSERT INTO Admin VALUES (2, 'Sue White', 'sue@email.com', 'pass3333', 1);
INSERT INTO Admin VALUES (3, 'James Brown', 'james@email.com', 'pass6666', 3);
INSERT INTO Admin VALUES (4, 'Lucy Green', 'lucy@email.com', 'pass7777', 4);
INSERT INTO Admin VALUES (5, 'Mark Black', 'mark@email.com', 'pass8888', 5);
 
INSERT INTO Player VALUES (1, 10, 3, 2, 850);
INSERT INTO Player VALUES (2, 7,  5, 1, 720);
INSERT INTO Player VALUES (3, 15, 2, 3, 950);
INSERT INTO Player VALUES (4, 4,  8, 2, 480);
INSERT INTO Player VALUES (5, 6,  6, 4, 600);
INSERT INTO Player VALUES (6, 12, 1, 1, 900);
INSERT INTO Player VALUES (14, 8,  4,  1, 780);
INSERT INTO Player VALUES (15, 12, 2,  3, 920);
INSERT INTO Player VALUES (16, 5,  7,  2, 510);
INSERT INTO Player VALUES (17, 15, 1,  2, 980);
INSERT INTO Player VALUES (18, 3,  9,  1, 390);
INSERT INTO Player VALUES (19, 10, 3,  4, 860);
INSERT INTO Player VALUES (20, 7,  5,  3, 710);
INSERT INTO Player VALUES (21, 2,  11, 0, 280);
INSERT INTO Player VALUES (22, 9,  4,  2, 800);
INSERT INTO Player VALUES (23, 14, 2,  1, 960);
INSERT INTO Player VALUES (24, 6,  6,  3, 620);
INSERT INTO Player VALUES (25, 11, 3,  2, 890);
INSERT INTO Player VALUES (26, 4,  8,  1, 450);
INSERT INTO Player VALUES (27, 13, 2,  3, 940);
INSERT INTO Player VALUES (28, 8,  5,  2, 760);
INSERT INTO Player VALUES (29, 1,  12, 0, 180);
INSERT INTO Player VALUES (30, 9,  3,  4, 820);
INSERT INTO Player VALUES (31, 16, 1,  1, 990);
INSERT INTO Player VALUES (32, 5,  7,  3, 530);
INSERT INTO Player VALUES (33, 10, 4,  2, 840);
INSERT INTO Player VALUES (34, 7,  6,  1, 690);
INSERT INTO Player VALUES (35, 3,  10, 0, 340);
INSERT INTO Player VALUES (36, 12, 2,  4, 910);
INSERT INTO Player VALUES (37, 6,  7,  2, 580);
INSERT INTO Player VALUES (38, 11, 3,  3, 870);
INSERT INTO Player VALUES (39, 4,  9,  1, 420);
INSERT INTO Player VALUES (40, 14, 1,  3, 970);
INSERT INTO Player VALUES (41, 8,  5,  2, 750);
INSERT INTO Player VALUES (42, 2,  10, 1, 250);
INSERT INTO Player VALUES (43, 9,  4,  3, 810);

-- needed to save games with AI
INSERT IGNORE INTO GameUser (UserID, Username, Email, Password) VALUES (0, 'AI', 'ai@system', 'N/A');
INSERT IGNORE INTO Player   (PlayerID) VALUES (0);
 
INSERT INTO TournamentManager VALUES (9,  3);
INSERT INTO TournamentManager VALUES (10, 5);
INSERT INTO TournamentManager VALUES (11, 2);
INSERT INTO TournamentManager VALUES (12, 1);
INSERT INTO TournamentManager VALUES (13, 4);
INSERT INTO TournamentManager VALUES (44, 2);
INSERT INTO TournamentManager VALUES (45, 1);
INSERT INTO TournamentManager VALUES (46, 3);
INSERT INTO TournamentManager VALUES (47, 0);
INSERT INTO TournamentManager VALUES (48, 4);
 
INSERT INTO Achievement VALUES (1, 'First Win',    'Win your first match');
INSERT INTO Achievement VALUES (2, 'Veteran',      'Play 50 matches');
INSERT INTO Achievement VALUES (3, 'Undefeated',   'Win 10 matches in a row');
INSERT INTO Achievement VALUES (4, 'Sharp Mind',   'Complete a match in under 20 moves');
INSERT INTO Achievement VALUES (5, 'Grand Master', 'Reach 1000 ranking points');
 
INSERT INTO PrizeMoney VALUES (1, 100);
INSERT INTO PrizeMoney VALUES (2, 250);
INSERT INTO PrizeMoney VALUES (3, 500);
INSERT INTO PrizeMoney VALUES (4, 1000);
INSERT INTO PrizeMoney VALUES (5, 2000);
 
INSERT INTO Tournament_Managed VALUES (1, 'Spring Cup',     2, '2025-03-01', 9,  1);
INSERT INTO Tournament_Managed VALUES (2, 'Summer Clash',   3, '2025-06-15', 9,  2);
INSERT INTO Tournament_Managed VALUES (3, 'Autumn Open',    1, '2025-09-10', 10, 3);
INSERT INTO Tournament_Managed VALUES (4, 'Winter Classic', 4, '2025-12-01', 10, 4);
INSERT INTO Tournament_Managed VALUES (5, 'Grand Finale',   5, '2025-11-20', 9,  5);
INSERT INTO Tournament_Managed VALUES (6,  'Spring Blitz',       1, '2025-03-10', 44, 1);
INSERT INTO Tournament_Managed VALUES (7,  'Summer Showdown',    2, '2025-05-20', 45, 2);
INSERT INTO Tournament_Managed VALUES (8,  'Autumn Rumble',      3, '2025-08-15', 46, 3);
INSERT INTO Tournament_Managed VALUES (9,  'Winter Warfare',     4, '2025-11-05', 47, 4);
INSERT INTO Tournament_Managed VALUES (10, 'Lightning Cup',      5, '2025-04-01', 48, 5);
INSERT INTO Tournament_Managed VALUES (11, 'Thunder League',     2, '2025-06-10', 44, 1);
INSERT INTO Tournament_Managed VALUES (12, 'Blaze Championship', 3, '2025-07-22', 45, 2);
INSERT INTO Tournament_Managed VALUES (13, 'Frost Open',         1, '2025-09-30', 46, 3);
INSERT INTO Tournament_Managed VALUES (14, 'Shadow Series',      4, '2025-10-15', 47, 4);
INSERT INTO Tournament_Managed VALUES (15, 'Apex Masters',       5, '2025-12-20', 48, 5);
 
INSERT INTO Match_Contained VALUES (1, '2025-03-05', '14:00:00', 1, 1);
INSERT INTO Match_Contained VALUES (2, '2025-03-06', '15:30:00', 3, 1);
INSERT INTO Match_Contained VALUES (3, '2025-06-20', '10:00:00', 2, 2);
INSERT INTO Match_Contained VALUES (4, '2025-09-12', '09:00:00', 5, 3);
INSERT INTO Match_Contained VALUES (5, '2025-12-05', '16:00:00', 6, 4);
INSERT INTO Match_Contained VALUES (6,  '2025-03-11', '10:00:00', 14, 6);
INSERT INTO Match_Contained VALUES (7,  '2025-03-11', '14:00:00', 17, 6);
INSERT INTO Match_Contained VALUES (8,  '2025-05-21', '09:00:00', 19, 7);
INSERT INTO Match_Contained VALUES (9,  '2025-05-21', '13:00:00', 18, 7);
INSERT INTO Match_Contained VALUES (10, '2025-08-16', '11:00:00', 23, 8);
INSERT INTO Match_Contained VALUES (11, '2025-08-16', '15:00:00', 22, 8);
INSERT INTO Match_Contained VALUES (12, '2025-11-06', '10:00:00', 27, 9);
INSERT INTO Match_Contained VALUES (13, '2025-11-06', '14:00:00', 26, 9);
INSERT INTO Match_Contained VALUES (14, '2025-04-02', '09:00:00', 31, 10);
INSERT INTO Match_Contained VALUES (15, '2025-04-02', '13:00:00', 30, 10);
INSERT INTO Match_Contained VALUES (16, '2025-06-11', '10:00:00', 34, 11);
INSERT INTO Match_Contained VALUES (17, '2025-06-11', '14:00:00', 37, 11);
INSERT INTO Match_Contained VALUES (18, '2025-07-23', '11:00:00', 38, 12);
INSERT INTO Match_Contained VALUES (19, '2025-07-23', '15:00:00', 41, 12);
INSERT INTO Match_Contained VALUES (20, '2025-10-01', '09:00:00', 42, 13);
INSERT INTO Match_Contained VALUES (21, '2025-10-01', '13:00:00', 15, 13);
INSERT INTO Match_Contained VALUES (22, '2025-10-16', '10:00:00', 17, 14);
INSERT INTO Match_Contained VALUES (23, '2025-10-16', '14:00:00', 19, 14);
INSERT INTO Match_Contained VALUES (24, '2025-12-21', '11:00:00', 23, 15);
INSERT INTO Match_Contained VALUES (25, '2025-12-21', '15:00:00', 20, 15);
 
INSERT INTO Move_MadeIn VALUES (1, 1, ',,,,X,,,,');
INSERT INTO Move_MadeIn VALUES (1, 2, 'O,,,X,,,,,' );
INSERT INTO Move_MadeIn VALUES (1, 3, 'O,,X,X,,,,,');
INSERT INTO Move_MadeIn VALUES (1, 4, 'O,,X,X,,,O,,');
INSERT INTO Move_MadeIn VALUES (1, 5, 'O,X,X,X,,,O,,');
INSERT INTO Move_MadeIn VALUES (1, 6, 'O,X,X,O,X,,,O,,');
INSERT INTO Move_MadeIn VALUES (1, 7, 'O,X,X,O,X,,,O,X,');
 
INSERT INTO Move_MadeIn VALUES (2, 1, ',X,,,,,,,');
INSERT INTO Move_MadeIn VALUES (2, 2, ',X,,O,,,,,');
INSERT INTO Move_MadeIn VALUES (2, 3, ',X,,O,,X,,,');
INSERT INTO Move_MadeIn VALUES (2, 4, ',X,,O,,X,,O,');
INSERT INTO Move_MadeIn VALUES (2, 5, ',X,,O,X,X,,O,');
 
INSERT INTO Move_MadeIn VALUES (3, 1, ',,,,X,,,,');
INSERT INTO Move_MadeIn VALUES (3, 2, 'O,,,X,,,,,' );
INSERT INTO Move_MadeIn VALUES (3, 3, 'O,,X,X,,,,,');
INSERT INTO Move_MadeIn VALUES (3, 4, 'O,O,X,X,,,,,');
INSERT INTO Move_MadeIn VALUES (3, 5, 'O,O,X,X,,,X,,');

INSERT INTO Move_MadeIn VALUES (4, 1, 'X,,,,,,,,');
INSERT INTO Move_MadeIn VALUES (4, 2, 'X,,,, O,,,,');
INSERT INTO Move_MadeIn VALUES (4, 3, 'X,X,,,O,,,,');
INSERT INTO Move_MadeIn VALUES (4, 4, 'X,X,,,O,,,O,');
INSERT INTO Move_MadeIn VALUES (4, 5, 'X,X,X,,O,,,O,');

INSERT INTO Move_MadeIn VALUES (5, 1, 'X,,,,,,,,');
INSERT INTO Move_MadeIn VALUES (5, 2, 'X,,,,O,,,,');
INSERT INTO Move_MadeIn VALUES (5, 3, 'X,,,X,O,,,,');
INSERT INTO Move_MadeIn VALUES (5, 4, 'X,,O,X,O,,,,');
INSERT INTO Move_MadeIn VALUES (5, 5, 'X,,O,X,O,,,,,');

INSERT INTO Move_MadeIn VALUES (6, 1, ',,,,X,,,,');
INSERT INTO Move_MadeIn VALUES (6, 2, ',,O,X,,,,,' );
INSERT INTO Move_MadeIn VALUES (6, 3, 'X,,O,X,,,,,');
INSERT INTO Move_MadeIn VALUES (6, 4, 'X,,O,X,,,O,,');
INSERT INTO Move_MadeIn VALUES (6, 5, 'X,,O,X,,,,O,X');

INSERT INTO Move_MadeIn VALUES (7, 1, ',,X,,,,,,');
INSERT INTO Move_MadeIn VALUES (7, 2, 'O,,X,,,,,,' );
INSERT INTO Move_MadeIn VALUES (7, 3, 'O,,X,,X,,,,');
INSERT INTO Move_MadeIn VALUES (7, 4, 'O,,X,O,X,,,,');
INSERT INTO Move_MadeIn VALUES (7, 5, 'O,,X,O,X,,,, X');
INSERT INTO Move_MadeIn VALUES (7, 6, 'O,,X,O,X,,,O,X');

INSERT INTO Move_MadeIn VALUES (8, 1, ',,,,X,,,,');
INSERT INTO Move_MadeIn VALUES (8, 2, 'O,,,X,,,,,' );
INSERT INTO Move_MadeIn VALUES (8, 3, 'O,,,X,,,X,,');
INSERT INTO Move_MadeIn VALUES (8, 4, 'O,O,,X,,,X,,');
INSERT INTO Move_MadeIn VALUES (8, 5, 'O,O,,X,,,X,,X');
INSERT INTO Move_MadeIn VALUES (8, 6, 'O,O,O,X,,,X,,X');

INSERT INTO Move_MadeIn VALUES (9, 1, 'X,,,,,,,,');
INSERT INTO Move_MadeIn VALUES (9, 2, 'X,,,,O,,,,');
INSERT INTO Move_MadeIn VALUES (9, 3, 'X,X,,,O,,,,');
INSERT INTO Move_MadeIn VALUES (9, 4, 'X,X,O,,O,,,,');
INSERT INTO Move_MadeIn VALUES (9, 5, 'X,X,O,,O,,,X,');
INSERT INTO Move_MadeIn VALUES (9, 6, 'X,X,O,,O,,,X,');

INSERT INTO Move_MadeIn VALUES (10, 1, 'X,,,,,,,,');
INSERT INTO Move_MadeIn VALUES (10, 2, 'X,,O,,,,,,' );
INSERT INTO Move_MadeIn VALUES (10, 3, 'X,,O,,X,,,,');
INSERT INTO Move_MadeIn VALUES (10, 4, 'X,,O,,X,O,,,');
INSERT INTO Move_MadeIn VALUES (10, 5, 'X,,O,,X,O,,, X');
INSERT INTO Move_MadeIn VALUES (10, 6, 'X,,O,,X,O,X,,O');

INSERT INTO Move_MadeIn VALUES (11, 1, ',,,,X,,,,');
INSERT INTO Move_MadeIn VALUES (11, 2, ',,,,X,,,O,,');
INSERT INTO Move_MadeIn VALUES (11, 3, 'X,,,X,,,,O,,');
INSERT INTO Move_MadeIn VALUES (11, 4, 'X,,,X,,,,O,O');
INSERT INTO Move_MadeIn VALUES (11, 5, 'X,,X,X,,,,O,O');
INSERT INTO Move_MadeIn VALUES (11, 6, 'X,,X,X,,,O,O,O');

INSERT INTO Move_MadeIn VALUES (12, 1, ',,,,,,X,,');
INSERT INTO Move_MadeIn VALUES (12, 2, 'O,,,,,X,,,' );
INSERT INTO Move_MadeIn VALUES (12, 3, 'O,,,,,,X,X,');
INSERT INTO Move_MadeIn VALUES (12, 4, 'O,,,,O,,X,X,');
INSERT INTO Move_MadeIn VALUES (12, 5, 'O,,,,O,,X,X,X');

INSERT INTO Move_MadeIn VALUES (13, 1, 'X,,,,,,,,');
INSERT INTO Move_MadeIn VALUES (13, 2, 'X,,,O,,,,,' );
INSERT INTO Move_MadeIn VALUES (13, 3, 'X,,X,O,,,,,');
INSERT INTO Move_MadeIn VALUES (13, 4, 'X,,X,O,O,,,,');
INSERT INTO Move_MadeIn VALUES (13, 5, 'X,,X,O,O,,,,X');
INSERT INTO Move_MadeIn VALUES (13, 6, 'X,,X,O,O,O,,,X');

INSERT INTO Move_MadeIn VALUES (14, 1, ',,X,,,,,,');
INSERT INTO Move_MadeIn VALUES (14, 2, 'O,,X,,,,,,' );
INSERT INTO Move_MadeIn VALUES (14, 3, 'O,,X,,,,X,,');
INSERT INTO Move_MadeIn VALUES (14, 4, 'O,,X,,O,,,X,,');
INSERT INTO Move_MadeIn VALUES (14, 5, 'O,X,X,,O,,,X,,');
INSERT INTO Move_MadeIn VALUES (14, 6, 'O,X,X,,O,,X,,,O');

INSERT INTO Move_MadeIn VALUES (15, 1, 'X,,,,,,,,');
INSERT INTO Move_MadeIn VALUES (15, 2, 'X,,O,,,,,,' );
INSERT INTO Move_MadeIn VALUES (15, 3, 'X,,O,,X,,,,');
INSERT INTO Move_MadeIn VALUES (15, 4, 'X,,O,,X,O,,,');
INSERT INTO Move_MadeIn VALUES (15, 5, 'X,,O,,X,O,X,,');
INSERT INTO Move_MadeIn VALUES (15, 6, 'X,,O,,X,O,X,,O');
 
INSERT INTO Move_MadeIn VALUES (16, 1, ',,,,X,,,,');
INSERT INTO Move_MadeIn VALUES (17, 1, 'X,,,,,,,,');
INSERT INTO Move_MadeIn VALUES (18, 1, ',,,,X,,,,');
INSERT INTO Move_MadeIn VALUES (19, 1, ',,,,,,X,,');
INSERT INTO Move_MadeIn VALUES (20, 1, ',,X,,,,,,');
INSERT INTO Move_MadeIn VALUES (21, 1, ',,,X,,,,,');
INSERT INTO Move_MadeIn VALUES (22, 1, ',X,,,,,,,');
INSERT INTO Move_MadeIn VALUES (23, 1, ',,,,,,,X,');
INSERT INTO Move_MadeIn VALUES (24, 1, ',,,,,X,,,');
INSERT INTO Move_MadeIn VALUES (25, 1, ',,,,,,,,X');
 
INSERT INTO Plays_inMatch VALUES (1, 1, 2);
INSERT INTO Plays_inMatch VALUES (2, 3, 4);
INSERT INTO Plays_inMatch VALUES (3, 2, 5);
INSERT INTO Plays_inMatch VALUES (4, 5, 6);
INSERT INTO Plays_inMatch VALUES (5, 6, 3);
INSERT INTO Plays_inMatch VALUES (6, 14, 15);
INSERT INTO Plays_inMatch VALUES (7, 16, 17);
INSERT INTO Plays_inMatch VALUES (8, 18, 19);
INSERT INTO Plays_inMatch VALUES (9, 20, 18);
INSERT INTO Plays_inMatch VALUES (10, 22, 23);
INSERT INTO Plays_inMatch VALUES (11, 24, 22);
INSERT INTO Plays_inMatch VALUES (12, 26, 27);
INSERT INTO Plays_inMatch VALUES (13, 28, 26);
INSERT INTO Plays_inMatch VALUES (14, 30, 31);
INSERT INTO Plays_inMatch VALUES (15, 32, 30);
INSERT INTO Plays_inMatch VALUES (16, 34, 35);
INSERT INTO Plays_inMatch VALUES (17, 36, 37);
INSERT INTO Plays_inMatch VALUES (18, 38, 39);
INSERT INTO Plays_inMatch VALUES (19, 40, 41);
INSERT INTO Plays_inMatch VALUES (20, 42, 43);
INSERT INTO Plays_inMatch VALUES (21, 14, 15);
INSERT INTO Plays_inMatch VALUES (22, 16, 17);
INSERT INTO Plays_inMatch VALUES (23, 18, 19);
INSERT INTO Plays_inMatch VALUES (24, 22, 23);
INSERT INTO Plays_inMatch VALUES (25, 20, 21);
 
INSERT INTO Awarded VALUES (1, 1, '2025-03-05');
INSERT INTO Awarded VALUES (3, 1, '2025-03-06');
INSERT INTO Awarded VALUES (3, 3, '2025-06-20');
INSERT INTO Awarded VALUES (2, 2, '2025-09-12');
INSERT INTO Awarded VALUES (6, 5, '2025-12-05');
INSERT INTO Awarded VALUES (14, 1, '2025-03-11');
INSERT INTO Awarded VALUES (17, 1, '2025-03-11');
INSERT INTO Awarded VALUES (19, 2, '2025-05-21');
INSERT INTO Awarded VALUES (23, 3, '2025-08-16');
INSERT INTO Awarded VALUES (31, 5, '2025-04-02');
INSERT INTO Awarded VALUES (27, 1, '2025-11-06');
INSERT INTO Awarded VALUES (34, 4, '2025-06-11');
INSERT INTO Awarded VALUES (42, 2, '2025-10-01');
 
INSERT INTO Edits VALUES (1, 1, '2025-01-10');
INSERT INTO Edits VALUES (2, 2, '2025-02-15');
INSERT INTO Edits VALUES (3, 3, '2025-03-20');
INSERT INTO Edits VALUES (4, 4, '2025-04-25');
INSERT INTO Edits VALUES (5, 5, '2025-05-30');
 
INSERT INTO Oversees VALUES (1, 1, 'Good standing');
INSERT INTO Oversees VALUES (1, 2, 'Occasional disputes');
INSERT INTO Oversees VALUES (2, 3, 'No issues');
INSERT INTO Oversees VALUES (3, 4, 'Under review');
INSERT INTO Oversees VALUES (4, 5, 'Excellent behavior');
INSERT INTO Oversees VALUES (5, 6, 'No issues');
 
INSERT INTO Joins VALUES (1, 1, '2025-02-20');
INSERT INTO Joins VALUES (2, 1, '2025-02-21');
INSERT INTO Joins VALUES (3, 2, '2025-05-30');
INSERT INTO Joins VALUES (4, 3, '2025-08-25');
INSERT INTO Joins VALUES (5, 4, '2025-11-10');
INSERT INTO Joins VALUES (6, 5, '2025-11-01');
INSERT INTO Joins VALUES (14, 6, '2025-02-28');
INSERT INTO Joins VALUES (15, 6, '2025-03-01');
INSERT INTO Joins VALUES (16, 6, '2025-03-01');
INSERT INTO Joins VALUES (17, 6, '2025-03-02');
INSERT INTO Joins VALUES (18, 7, '2025-05-01');
INSERT INTO Joins VALUES (19, 7, '2025-05-02');
INSERT INTO Joins VALUES (20, 7, '2025-05-03');
INSERT INTO Joins VALUES (21, 7, '2025-05-03');
INSERT INTO Joins VALUES (22, 8, '2025-08-01');
INSERT INTO Joins VALUES (23, 8, '2025-08-02');
INSERT INTO Joins VALUES (24, 8, '2025-08-03');
INSERT INTO Joins VALUES (25, 8, '2025-08-04');
INSERT INTO Joins VALUES (26, 9, '2025-10-20');
INSERT INTO Joins VALUES (27, 9, '2025-10-21');
INSERT INTO Joins VALUES (28, 9, '2025-10-22');
INSERT INTO Joins VALUES (29, 9, '2025-10-22');
INSERT INTO Joins VALUES (30, 10, '2025-03-15');
INSERT INTO Joins VALUES (31, 10, '2025-03-16');
INSERT INTO Joins VALUES (32, 10, '2025-03-17');
INSERT INTO Joins VALUES (33, 10, '2025-03-17');
INSERT INTO Joins VALUES (34, 11, '2025-05-25');
INSERT INTO Joins VALUES (35, 11, '2025-05-26');
INSERT INTO Joins VALUES (36, 11, '2025-05-27');
INSERT INTO Joins VALUES (37, 11, '2025-05-27');
INSERT INTO Joins VALUES (38, 12, '2025-07-10');
INSERT INTO Joins VALUES (39, 12, '2025-07-11');
INSERT INTO Joins VALUES (40, 12, '2025-07-12');
INSERT INTO Joins VALUES (41, 12, '2025-07-12');
INSERT INTO Joins VALUES (42, 13, '2025-09-15');
INSERT INTO Joins VALUES (43, 13, '2025-09-16');
INSERT INTO Joins VALUES (14, 13, '2025-09-17');
INSERT INTO Joins VALUES (15, 13, '2025-09-17');
INSERT INTO Joins VALUES (16, 14, '2025-10-01');
INSERT INTO Joins VALUES (17, 14, '2025-10-02');
INSERT INTO Joins VALUES (18, 14, '2025-10-03');
INSERT INTO Joins VALUES (19, 14, '2025-10-03');
INSERT INTO Joins VALUES (20, 15, '2025-12-01');
INSERT INTO Joins VALUES (21, 15, '2025-12-02');
INSERT INTO Joins VALUES (22, 15, '2025-12-03');
INSERT INTO Joins VALUES (23, 15, '2025-12-03');

-- ------------------------------------------------------------
-- TRIGGERS (Were messing with the same game logic )
-- ------------------------------------------------------------

-- Drop triggers if they already exist
-- DROP TRIGGER IF EXISTS after_match_winner_update;
-- DROP TRIGGER IF EXISTS after_tournament_insert;

-- -- ------------------------------------------------------------
-- -- Trigger 1: Auto-update Player stats when a Match Winner is set,
-- -- increment TotalWins for the winner and TotalLosses for the loser

-- DELIMITER $$

-- CREATE TRIGGER after_match_winner_update
-- AFTER UPDATE ON Match_Contained
-- FOR EACH ROW
-- BEGIN
-- 	-- Fire if winner is just set
-- 	IF OLD.Winner_ID IS NULL AND NEW.Winner_ID IS NOT NULL THEN

-- 		UPDATE Player
-- 		SET TotalWins = TotalWins + 1
-- 		WHERE PlayerID = NEW.Winner_ID;

-- 		UPDATE Player
-- 		SET TotalLosses = TotalLosses + 1
-- 		WHERE PlayerID = (
-- 			SELECT CASE
-- 				WHEN Player1_ID = NEW.Winner_ID THEN Player2_ID
-- 				ELSE Player1_ID
-- 			END
-- 			FROM Plays_inMatch
-- 			WHERE MatchID = NEW.MatchID
-- 		);

-- 	END IF;
-- END$$

-- -- ------------------------------------------------------------
-- -- Trigger 2: Auto-increment Tournaments_Organized for a Manager,
-- -- automatically update the manager's Tournaments_Organized count

-- CREATE TRIGGER after_tournament_insert
-- AFTER INSERT ON Tournament_Managed
-- FOR EACH ROW
-- BEGIN
-- 	UPDATE TournamentManager
-- 	SET Tournaments_Organized = Tournaments_Organized + 1
-- 	WHERE ManagerID = NEW.ManagerID;
-- END$$

-- DELIMITER ;
