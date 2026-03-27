<?php

$host = 'localhost';
$user = 'root';
$db   = 'tictactoe';

//List of possible passwords
$passwords = ['', 'root'];

//test each password and break loop if the connection is successful
foreach ($passwords as $pass) {
    $conn = mysqli_connect($host, $user, $pass, $db);

    if ($conn) {
        break;
    }
}

if (!$conn) {
    die(json_encode([
        'error' => 'Connection failed: ' . mysqli_connect_error()
    ]));
}

?>