<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Make sure user is logged in
if (!isset($_SESSION['userID'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$userID = $_SESSION['userID'];

// Get form data
$username        = $_POST['username'] ?? '';
$email           = $_POST['email'] ?? '';
$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword     = $_POST['newPassword'] ?? '';

// 1. Get current user data
$stmt = mysqli_prepare($conn, "SELECT Username, Email, Password FROM GameUser WHERE UserID = ?");
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo json_encode(['success' => false, 'error' => 'User not found']);
    exit;
}

// 2. Verify current password
if (($currentPassword !== $user['Password'])) {
    echo json_encode(['success' => false, 'error' => 'Incorrect current password']);
    exit;
}

// 3. Track updates
$updates = [];
$params = [];
$types = "";

// Username update
if (!empty($username) && $username !== $user['Username']) {
    $updates[] = "Username = ?";
    $params[] = $username;
    $types .= "s";
}

// Email update
if (!empty($email) && $email !== $user['Email']) {
    $updates[] = "Email = ?";
    $params[] = $email;
    $types .= "s";
}

// Password update
if (!empty($newPassword)) {
    $updates[] = "Password = ?";
    $params[] = $newPassword;
    $types .= "s";
}

// 4. If nothing changed
if (empty($updates)) {
    echo json_encode(['success' => false, 'error' => 'No changes made']);
    exit;
}

// 5. Build dynamic query
$sql = "UPDATE GameUser SET " . implode(", ", $updates) . " WHERE UserID = ?";
$params[] = $userID;
$types .= "i";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Update failed']);
}

mysqli_close($conn);
?>