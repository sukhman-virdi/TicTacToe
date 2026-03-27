<?php
session_start();
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

$username = trim($_POST['username'] ?? '');
$password = $_POST['password']      ?? '';

if (!$username || !$password) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
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

// Check if already a manager
$r = mysqli_query($conn, "SELECT 1 FROM TournamentManager WHERE ManagerID = $userID");
if (mysqli_num_rows($r) > 0) {
    echo json_encode(['success' => false, 'error' => 'This account is already a Tournament Manager']);
    exit;
}

// Add manager role
$stmt2 = mysqli_prepare($conn, "INSERT INTO TournamentManager (ManagerID) VALUES (?)");
mysqli_stmt_bind_param($stmt2, 'i', $userID);

if (!mysqli_stmt_execute($stmt2)) {
    echo json_encode(['success' => false, 'error' => mysqli_stmt_error($stmt2)]);
    exit;
}

// Auto-login with upgraded account
$_SESSION['userID']   = $userID;
$_SESSION['username'] = $username;

mysqli_close($conn);

echo json_encode(['success' => true]);
?>
