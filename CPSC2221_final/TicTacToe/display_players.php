<?php
header('Content-Type: application/json');
session_start();

$conn = new mysqli("localhost", "root", "", "tictactoe");

// Top 10 players
$query = "SELECT p.PlayerID, gu.Username, p.TotalWins, p.TotalLosses, p.TotalDraws, p.RankingPoints
          FROM Player p
          JOIN GameUser gu ON p.PlayerID = gu.UserID
          ORDER BY p.RankingPoints DESC LIMIT 10";

$result = $conn->query($query);

$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}

// Logged-in user (if exists)
$currentUser = null;

if (isset($_SESSION['userID'])) {
    $id = $_SESSION['userID'];

    $stmt = $conn->prepare(
        "SELECT p.PlayerID, gu.Username, p.TotalWins, p.TotalLosses, p.TotalDraws, p.RankingPoints
         FROM Player p
         JOIN GameUser gu ON p.PlayerID = gu.UserID
         WHERE p.PlayerID = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $currentUser = $stmt->get_result()->fetch_assoc();
}

echo json_encode([
    'topPlayers' => $players,
    'currentUser' => $currentUser
]);