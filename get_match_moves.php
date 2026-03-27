<?php
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

$matchID = (int) ($_GET['match_id'] ?? 0);

if (!$matchID) {
    echo json_encode(['moves' => []]);
    exit;
}

$stmt = mysqli_prepare($conn,
    "SELECT Position FROM Move_MadeIn WHERE MatchID = ? ORDER BY Move_Number ASC");
mysqli_stmt_bind_param($stmt, 'i', $matchID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$moves = [];
while ($row = mysqli_fetch_assoc($result)) {
    $moves[] = $row['Position'];
}

echo json_encode(['moves' => $moves]);
mysqli_close($conn);
?>
