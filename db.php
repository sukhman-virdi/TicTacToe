<?php

$host = 'localhost';
$user = 'root';
$db   = 'tictactoe';

$password = 'root';

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {

    $conn = mysqli_connect($host, $user, "", $db);
    
    if (!$conn) {

        die(json_encode([
            'error' => 'Connection failed: ' . mysqli_connect_error()
        ]));
    }
}

?>