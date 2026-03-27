<?php
include 'db.php';

$id = $_GET['id'];

$query = "
    SELECT
        m.MatchID,
        m.Match_Date,
        m.Match_Time,
        CASE WHEN m.TournamentID IS NULL THEN 'AI Game' ELSE t.Name END AS TournamentName,
        CASE
            WHEN m.Winner_ID = ? THEN 'Win'
            WHEN m.Winner_ID IS NULL THEN 'Draw'
            ELSE 'Loss'
        END AS Result,
        CASE
            WHEN pi.Player1_ID = ? THEN g2.Username
            ELSE g1.Username
        END AS Opponent
    FROM Match_Contained m
    JOIN Plays_inMatch pi ON m.MatchID = pi.MatchID
    LEFT JOIN Tournament_Managed t ON m.TournamentID = t.TournamentID
    JOIN GameUser g1 ON pi.Player1_ID = g1.UserID
    JOIN GameUser g2 ON pi.Player2_ID = g2.UserID
    WHERE pi.Player1_ID = ? OR pi.Player2_ID = ?
    ORDER BY m.Match_Date DESC, m.Match_Time DESC
    LIMIT 10
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'iiii', $id, $id, $id, $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$games = [];
while ($row = mysqli_fetch_assoc($result)) {
    $games[] = $row;
}

header('Content-Type: application/json');
echo json_encode($games);

mysqli_close($conn);
?>