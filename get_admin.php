<?php
include 'db.php';

$id = $_GET['id'];

$query = "
    SELECT AdminID, Name, Email, Level
    FROM Admin
    WHERE AdminID = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode($admin);

mysqli_close($conn);
?>