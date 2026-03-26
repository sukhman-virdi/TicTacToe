<?php
session_start();
include 'db.php';
header('Content-Type: application/json');




$avgQuery = "SELECT AVG(RankingPoints) AS OverallAvg FROM Player";
$avgResult = $conn->query($avgQuery);
$avgRow = $avgResult->fetch_assoc();
$avgPoints = round($avgRow['OverallAvg']);

$totalPlayersQuery = "SELECT COUNT(*) AS TotalPlayers FROM Player";
$totalResult = $conn->query($totalPlayersQuery);
$totalRow = $totalResult->fetch_assoc();
$totalPlayers = (int)$totalRow['TotalPlayers'];


echo json_encode([
    'avgPoints' => $avgPoints,
    'totalPlayers' => $totalPlayers
]);