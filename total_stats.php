<?php
session_start();
include 'db.php';
header('Content-Type: application/json');
 
// Aggregation 1 — Average ranking points across all players
$avgQuery  = "SELECT AVG(RankingPoints) AS OverallAvg FROM Player";
$avgResult = $conn->query($avgQuery);
$avgRow    = $avgResult->fetch_assoc();
$avgPoints = round($avgRow['OverallAvg']);
 
// Aggregation 2 — Total number of players
$totalPlayersQuery = "SELECT COUNT(*) AS TotalPlayers FROM Player";
$totalResult       = $conn->query($totalPlayersQuery);
$totalRow          = $totalResult->fetch_assoc();
$totalPlayers      = (int)$totalRow['TotalPlayers'];
 
// Nested Aggregation
// Find difficulty levels where the average player ranking is above the overall average
$nestedQuery = "SELECT pm.Difficulty, AVG(p.RankingPoints) AS AvgRanking
                FROM Player p
                JOIN Joins j ON p.PlayerID = j.PlayerID
                JOIN Tournament_Managed tm ON j.TournamentID = tm.TournamentID
                JOIN PrizeMoney pm ON tm.Difficulty = pm.Difficulty
                GROUP BY pm.Difficulty
                HAVING AVG(p.RankingPoints) > (
                    SELECT AVG(RankingPoints) FROM Player
                )
                ORDER BY pm.Difficulty";
 
$nestedResult = $conn->query($nestedQuery);
$aboveAvgDifficulties = [];
if ($nestedResult) {
    while ($row = $nestedResult->fetch_assoc()) {
        $aboveAvgDifficulties[] = [
            'Difficulty' => $row['Difficulty'],
            'AvgRanking' => round($row['AvgRanking'])
        ];
    }
}
 
echo json_encode([
    'avgPoints'            => $avgPoints,
    'totalPlayers'         => $totalPlayers,
    'aboveAvgDifficulties' => $aboveAvgDifficulties
]);
?>
