<?php
session_start();
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

$playerID     = (int) $_SESSION['userID'];
$AI_PLAYER_ID = 0;

$body   = json_decode(file_get_contents('php://input'), true);
$result = $body['result'];
$moves  = $body['moves'] ?? [];   // [ ["X,,O,..."], ["X,O,O,..."], ... ]

// Winner ID
if ($result === 'win')      $winnerID = $playerID;
elseif ($result === 'loss') $winnerID = $AI_PLAYER_ID;
else                        $winnerID = null;

// Generate next MatchID manually
$row     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(MAX(MatchID), 0) + 1 AS nextID FROM Match_Contained"));
$matchID = (int) $row['nextID'];

// Insert match (TournamentID NULL = AI game)
if ($winnerID === null) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO Match_Contained (MatchID, Match_Date, Match_Time, Winner_ID, TournamentID)
         VALUES (?, CURDATE(), CURTIME(), NULL, NULL)");
    mysqli_stmt_bind_param($stmt, 'i', $matchID);
} else {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO Match_Contained (MatchID, Match_Date, Match_Time, Winner_ID, TournamentID)
         VALUES (?, CURDATE(), CURTIME(), ?, NULL)");
    mysqli_stmt_bind_param($stmt, 'ii', $matchID, $winnerID);
}
mysqli_stmt_execute($stmt);

// Insert players
$stmt2 = mysqli_prepare($conn,
    "INSERT INTO Plays_inMatch (MatchID, Player1_ID, Player2_ID) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt2, 'iii', $matchID, $playerID, $AI_PLAYER_ID);
mysqli_stmt_execute($stmt2);

// Insert moves — each move stores the board state as a string in Position column
$stmtMove = mysqli_prepare($conn,
    "INSERT INTO Move_MadeIn (MatchID, Move_Number, Position) VALUES (?, ?, ?)");
foreach ($moves as $moveNumber => $boardString) {
    $moveNum = $moveNumber + 1;  // 1-based
    mysqli_stmt_bind_param($stmtMove, 'iis', $matchID, $moveNum, $boardString);
    mysqli_stmt_execute($stmtMove);
}

// Update stats
$columnMap = ['win' => 'TotalWins', 'loss' => 'TotalLosses', 'draw' => 'TotalDraws'];
$pointsMap = ['win' => 3, 'loss' => 0, 'draw' => 1];
$statColumn = $columnMap[$result];
$points     = $pointsMap[$result];

$stmt3 = mysqli_prepare($conn,
    "UPDATE Player SET $statColumn = $statColumn + 1, RankingPoints = RankingPoints + ? WHERE PlayerID = ?");
mysqli_stmt_bind_param($stmt3, 'ii', $points, $playerID);
mysqli_stmt_execute($stmt3);

mysqli_close($conn);

echo json_encode(['success' => true]);