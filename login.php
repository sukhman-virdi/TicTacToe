<?php
session_start();
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

$login    = trim($_POST['login']    ?? '');
$password = trim($_POST['password'] ?? '');

if (!$login || !$password) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

// Match by username OR email
$stmt = mysqli_prepare($conn, "SELECT UserID, Username FROM GameUser WHERE (Username = ? OR Email = ?) AND Password = ?");
mysqli_stmt_bind_param($stmt, 'sss', $login, $login, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);

if (!$user) {
    echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
    exit;
}

$userID   = (int) $user['UserID'];
$username = $user['Username'];

// Check roles
$rPlayer  = mysqli_query($conn, "SELECT 1 FROM Player WHERE PlayerID = $userID");
$rManager = mysqli_query($conn, "SELECT 1 FROM TournamentManager WHERE ManagerID = $userID");
$isPlayer  = mysqli_num_rows($rPlayer)  > 0;
$isManager = mysqli_num_rows($rManager) > 0;

// Set session
$_SESSION['userID']   = $userID;
$_SESSION['username'] = $username;

mysqli_close($conn);

echo json_encode([
    'success'   => true,
    'userID'    => $userID,
    'username'  => $username,
    'isPlayer'  => $isPlayer,
    'isManager' => $isManager,
]);
?>