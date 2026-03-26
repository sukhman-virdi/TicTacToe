<?php
ob_start();
session_start();
include 'db.php';
ob_clean();
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$id = $_SESSION['userID'];

$stmt = mysqli_prepare($conn, "SELECT Username, Email FROM GameUser WHERE UserID = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

echo json_encode($user);
mysqli_close($conn);
?>