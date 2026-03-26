<?php
include 'db.php';

$id = $_GET['id'];

$query = "
    SELECT g.UserID, g.Username, g.Email,
           p.TotalWins, p.TotalLosses, p.TotalDraws, p.RankingPoints
    FROM GameUser g
    JOIN Player p ON g.UserID = p.PlayerID
    WHERE g.UserID = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$player = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode($player);

mysqli_close($conn);
?>