<?php
$conn = mysqli_connect('localhost', 'root', '', 'tictactoe');

if (!$conn) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}
?>