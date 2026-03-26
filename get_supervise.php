<?php
include 'db.php';

$id = $_GET['id'];

$query = "
    SELECT TournamentID, Name, ManagerID
    FROM Tournament_Managed 
    WHERE SupervisorID = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tournaments = mysqli_fetch_all($result, MYSQLI_ASSOC);
if (!$tournaments) $tournaments = [];

header('Content-Type: application/json');
echo json_encode($tournaments);

mysqli_close($conn);
?>