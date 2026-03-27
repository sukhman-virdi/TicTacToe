<?php
session_start();
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

$userID = (int) ($_SESSION['userID'] ?? 0);

$query = "
    SELECT
        t.TournamentID,
        t.Name,
        t.StartDate,
        t.Difficulty,
        p.PrizeMoney,
        g.Username AS ManagerName,
        CASE WHEN j.PlayerID IS NOT NULL THEN '1' ELSE '0' END AS AlreadyJoined
    FROM Tournament_Managed t
    JOIN PrizeMoney p ON t.Difficulty = p.Difficulty
    JOIN TournamentManager tm ON t.ManagerID = tm.ManagerID
    JOIN GameUser g ON tm.ManagerID = g.UserID
    LEFT JOIN Joins j ON j.TournamentID = t.TournamentID AND j.PlayerID = ?
    ORDER BY t.StartDate ASC
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$tournaments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tournaments[] = $row;
}

echo json_encode($tournaments);
mysqli_close($conn);
?>
