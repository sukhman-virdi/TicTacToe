<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (isset($_SESSION['userID'])) {
    $id = (int) $_SESSION['userID'];

    // Check if user is a Player
    $r1 = mysqli_query($conn, "SELECT 1 FROM Player WHERE PlayerID = $id");
    $isPlayer = mysqli_num_rows($r1) > 0;

    // Check if user is a Tournament Manager
    $r2 = mysqli_query($conn, "SELECT 1 FROM TournamentManager WHERE ManagerID = $id");
    $isManager = mysqli_num_rows($r2) > 0;

    echo json_encode([
        'loggedIn'  => true,
        'userID'    => $_SESSION['userID'],
        'username'  => $_SESSION['username'],
        'isPlayer'  => $isPlayer,
        'isManager' => $isManager,
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}

mysqli_close($conn);
?>