<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['userID'])) {
    echo json_encode([
        'loggedIn' => true,
        'userID'   => $_SESSION['userID'],
        'username' => $_SESSION['username']
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>