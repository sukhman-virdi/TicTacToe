<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$user = 'root';
$db   = 'tictactoe';
$passwords = ['root', '', 'password'];

function tryConnect($host, $user, $pass, $db) {
    try {
        $conn = mysqli_connect($host, $user, $pass, $db);
        return $conn;
    } catch (mysqli_sql_exception $e) {
        return null;
    }
}

foreach($passwords as $p){
    $conn = tryConnect($host,$user,$p,$db);
    if($conn) break;
}

if (!$conn) {
    die(json_encode(['error' => 'Both connection attempts failed.']));
}
?>