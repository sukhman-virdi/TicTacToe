<?php
session_start();
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

$username   = trim($_POST['username']   ?? '');
$password   = $_POST['password']        ?? '';
$addPlayer  = ($_POST['addPlayer']      ?? '0') === '1';
$addManager = ($_POST['addManager']     ?? '0') === '1';

if (!$username || !$password) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

if (!$addPlayer && !$addManager) {
    echo json_encode(['success' => false, 'error' => 'Select at least one role to add']);
    exit;
}

// Verify credentials
$stmt = mysqli_prepare($conn, "SELECT UserID FROM GameUser WHERE Username = ? AND Password = ?");
mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);

if (!$user) {
    echo json_encode(['success' => false, 'error' => 'Incorrect username or password']);
    exit;
}

$userID = (int) $user['UserID'];
$added  = [];

// Add Player role if requested and not already one
if ($addPlayer) {
    $r = mysqli_query($conn, "SELECT 1 FROM Player WHERE PlayerID = $userID");
    if (mysqli_num_rows($r) > 0) {
        $added[] = 'already a Player';
    } else {
        $s = mysqli_prepare($conn, "INSERT INTO Player (PlayerID) VALUES (?)");
        mysqli_stmt_bind_param($s, 'i', $userID);
        mysqli_stmt_execute($s);
        $added[] = 'Player role added';
    }
}

// Add Manager role if requested and not already one
if ($addManager) {
    $r = mysqli_query($conn, "SELECT 1 FROM TournamentManager WHERE ManagerID = $userID");
    if (mysqli_num_rows($r) > 0) {
        $added[] = 'already a Manager';
    } else {
        $s = mysqli_prepare($conn, "INSERT INTO TournamentManager (ManagerID) VALUES (?)");
        mysqli_stmt_bind_param($s, 'i', $userID);
        mysqli_stmt_execute($s);
        $added[] = 'Manager role added';
    }
}

// Auto-login
$_SESSION['userID']   = $userID;
$_SESSION['username'] = $username;

mysqli_close($conn);

echo json_encode([
    'success' => true,
    'message' => implode(', ', $added) . '.',
]);
?>