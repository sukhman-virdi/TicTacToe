<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Make sure user is logged in
if (!isset($_SESSION['adminID'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$AdminID = $_SESSION['adminID'];

// Get form data
$name        = $_POST['name'] ?? '';
$email           = $_POST['email'] ?? '';
$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword     = $_POST['newPassword'] ?? '';

// 1. Get current user data
$stmt = mysqli_prepare($conn, "SELECT Name, Email, Password FROM Admin WHERE AdminID = ?");
mysqli_stmt_bind_param($stmt, "i", $AdminID);
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
if (!empty($name) && $name !== $user['Name']) {
    $updates[] = "Name = ?";
    $params[] = $name;
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
$sql = "UPDATE Admin SET " . implode(", ", $updates) . " WHERE AdminID = ?";
$params[] = $AdminID;
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