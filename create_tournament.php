<?php
session_start();
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

if (!isset($_SESSION['userID'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$managerID = (int) $_SESSION['userID'];

// Verify user is a tournament manager
$r = mysqli_query($conn, "SELECT 1 FROM TournamentManager WHERE ManagerID = $managerID");
if (mysqli_num_rows($r) === 0) {
    echo json_encode(['success' => false, 'message' => 'You must be a Tournament Manager to create tournaments']);
    exit;
}

$body       = json_decode(file_get_contents('php://input'), true);
$name       = trim($body['name'] ?? '');
$startDate  = $body['start_date'] ?? '';
$difficulty = (int) ($body['difficulty'] ?? 0);
$supervisorID = (int) ($body['SupervisorID'] ?? 0);

if (!$name || !$startDate || !$difficulty) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if ($difficulty < 1 || $difficulty > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid difficulty']);
    exit;
}

// Get a supervisor (first available admin)
if($supervisorID===0){
    $adminRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AdminID FROM Admin LIMIT 1"));
    if (!$adminRow) {
        echo json_encode(['success' => false, 'message' => 'No admin available to supervise']);
        exit;
    }
    $supervisorID = (int) $adminRow['AdminID'];
}


// Generate next TournamentID manually
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(MAX(TournamentID), 0) + 1 AS nextID FROM Tournament_Managed"));
$tournamentID = (int) $row['nextID'];

$stmt = mysqli_prepare($conn,
    "INSERT INTO Tournament_Managed (TournamentID, Name, Difficulty, StartDate, ManagerID, SupervisorID)
     VALUES (?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'isisii', $tournamentID, $name, $difficulty, $startDate, $managerID, $supervisorID);

if (mysqli_stmt_execute($stmt)) {
    // Increment manager's tournament count
    mysqli_query($conn, "UPDATE TournamentManager SET Tournaments_Organized = Tournaments_Organized + 1 WHERE ManagerID = $managerID");
    echo json_encode(['success' => true, 'tournament_id' => $tournamentID]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_stmt_error($stmt)]);
}

mysqli_close($conn);
?>