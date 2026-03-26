<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['adminID'])) {
    echo json_encode([
        'loggedIn' => true,
        'adminID' => $_SESSION['adminID'],
        'name' => $_SESSION['name']
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>