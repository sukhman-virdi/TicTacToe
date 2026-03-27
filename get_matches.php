<?php
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

$player1 = trim($_GET['player1'] ?? '');
$player2 = trim($_GET['player2'] ?? '');

if (!$player1) {
    echo json_encode(['error' => 'Player 1 is required']);
    exit;
}

if ($player2) {
    // Both players specified — find matches between them
    $query = "
        SELECT
            m.MatchID,
            m.Match_Date,
            g1.Username AS Player1,
            g2.Username AS Player2,
            gw.Username AS WinnerName
        FROM Match_Contained m
        JOIN Plays_inMatch pi ON m.MatchID = pi.MatchID
        JOIN GameUser g1 ON pi.Player1_ID = g1.UserID
        JOIN GameUser g2 ON pi.Player2_ID = g2.UserID
        LEFT JOIN GameUser gw ON m.Winner_ID = gw.UserID
        WHERE (g1.Username = ? AND g2.Username = ?)
           OR (g1.Username = ? AND g2.Username = ?)
        ORDER BY m.Match_Date DESC, m.Match_Time DESC
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssss', $player1, $player2, $player2, $player1);
} else {
    // Only player1 specified — find all their matches
    $query = "
        SELECT
            m.MatchID,
            m.Match_Date,
            g1.Username AS Player1,
            g2.Username AS Player2,
            gw.Username AS WinnerName
        FROM Match_Contained m
        JOIN Plays_inMatch pi ON m.MatchID = pi.MatchID
        JOIN GameUser g1 ON pi.Player1_ID = g1.UserID
        JOIN GameUser g2 ON pi.Player2_ID = g2.UserID
        LEFT JOIN GameUser gw ON m.Winner_ID = gw.UserID
        WHERE g1.Username = ? OR g2.Username = ?
        ORDER BY m.Match_Date DESC, m.Match_Time DESC
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $player1, $player1);
}

mysqli_stmt_execute($stmt);
$result  = mysqli_stmt_get_result($stmt);
$matches = [];
while ($row = mysqli_fetch_assoc($result)) {
    $matches[] = $row;
}

echo json_encode($matches);
mysqli_close($conn);
?>
