<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$user = 'root';
$db   = 'tictactoe';

function tryConnect($host, $user, $pass, $db) {
    try {
        $conn = mysqli_connect($host, $user, $pass, $db);
        return $conn;
    } catch (mysqli_sql_exception $e) {
        return null;
    }
}

$conn = tryConnect($host, $user, "", $db)
     ?? tryConnect($host, $user, "root", $db);

if (!$conn) {
    die(json_encode(['error' => 'Both connection attempts failed.']));
}
?>