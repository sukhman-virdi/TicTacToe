<?php
session_start();
include 'db.php';

header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_OFF);

$username  = trim($_POST['username']  ?? '');
$email     = trim($_POST['email']     ?? '');
$password  = $_POST['password']       ?? '';
$isPlayer  = ($_POST['isPlayer']      ?? '0') === '1';
$isManager = ($_POST['isManager']     ?? '0') === '1';

if (!$username || !$email || !$password) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

if (!$isPlayer && !$isManager) {
    echo json_encode(['success' => false, 'error' => 'Select at least one account type']);
    exit;
}

// Generate next UserID manually
$row    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(MAX(UserID), 0) + 1 AS nextID FROM GameUser"));
$userID = (int) $row['nextID'];

// Insert into GameUser
$stmt = mysqli_prepare($conn, "INSERT INTO GameUser (UserID, Username, Email, Password) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'isss', $userID, $username, $email, $password);

if (!mysqli_stmt_execute($stmt)) {
    $err = mysqli_stmt_error($stmt);
    if (str_contains($err, 'Username')) {
        echo json_encode(['success' => false, 'error' => 'Username already taken']);
    } elseif (str_contains($err, 'Email')) {
        echo json_encode(['success' => false, 'error' => 'Email already registered']);
    } else {
        echo json_encode(['success' => false, 'error' => $err]);
    }
    exit;
}

// Insert into Player if selected
if ($isPlayer) {
    $stmt2 = mysqli_prepare($conn, "INSERT INTO Player (PlayerID) VALUES (?)");
    mysqli_stmt_bind_param($stmt2, 'i', $userID);
    mysqli_stmt_execute($stmt2);
}

// Insert into TournamentManager if selected
if ($isManager) {
    $stmt3 = mysqli_prepare($conn, "INSERT INTO TournamentManager (ManagerID) VALUES (?)");
    mysqli_stmt_bind_param($stmt3, 'i', $userID);
    mysqli_stmt_execute($stmt3);
}

// Auto-login — set session
$_SESSION['userID']   = $userID;
$_SESSION['username'] = $username;

mysqli_close($conn);

echo json_encode(['success' => true]);
?>