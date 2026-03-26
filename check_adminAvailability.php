<?php
include 'db.php';

$field = $_GET['field'];
$value = $_GET['value'];

// only allow checking Username or Email for security
if ($field !== 'Name' && $field !== 'Email') {
    echo json_encode(['error' => 'Invalid field']);
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT AdminID FROM Admin WHERE $field = ?");
mysqli_stmt_bind_param($stmt, 's', $value);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

header('Content-Type: application/json');
echo json_encode(['taken' => mysqli_stmt_num_rows($stmt) > 0]);

mysqli_close($conn);
?>