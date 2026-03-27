<?php
include 'db.php';

$id = $_GET['id'];

$query = "
    SELECT ManagerID, Tournaments_Organized, Username, Email
    FROM TournamentManager
    JOIN GameUser ON ManagerID = UserID
    WHERE ManagerID = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$manager = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode($manager);

mysqli_close($conn);
?>