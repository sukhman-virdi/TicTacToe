<?php
session_start();
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

if (!isset($_SESSION['userID'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$playerID      = (int) $_SESSION['userID'];
$body          = json_decode(file_get_contents('php://input'), true);
$tournamentID  = (int) ($body['tournament_id'] ?? 0);

if (!$tournamentID) {
    echo json_encode(['success' => false, 'message' => 'Invalid tournament']);
    exit;
}

// Check player exists
$r = mysqli_query($conn, "SELECT 1 FROM Player WHERE PlayerID = $playerID");
if (mysqli_num_rows($r) === 0) {
    echo json_encode(['success' => false, 'message' => 'You must be a Player to join tournaments']);
    exit;
}

// Check not already joined
$r2 = mysqli_query($conn, "SELECT 1 FROM Joins WHERE PlayerID = $playerID AND TournamentID = $tournamentID");
if (mysqli_num_rows($r2) > 0) {
    echo json_encode(['success' => false, 'message' => 'You have already joined this tournament']);
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO Joins (PlayerID, TournamentID, JoinDate) VALUES (?, ?, CURDATE())");
mysqli_stmt_bind_param($stmt, 'ii', $playerID, $tournamentID);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_stmt_error($stmt)]);
}

mysqli_close($conn);
?>
