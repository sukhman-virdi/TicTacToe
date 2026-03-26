<?php
session_start(); // add this as the very first line
include 'db.php';

$login    = $_POST['login'];
$password = $_POST['password'];

if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
    $stmt = mysqli_prepare($conn,
        "SELECT AdminID, Name, Password FROM Admin WHERE Email = ?"
    );
} else {
    $stmt = mysqli_prepare($conn,
        "SELECT AdminID, Name, Password FROM Admin WHERE Name = ?"
    );
}

mysqli_stmt_bind_param($stmt, 's', $login);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);

header('Content-Type: application/json');

if (!$user) {
    echo json_encode(['error' => 'No admin account found with that username or email']);
    exit;
}

if ($password !== $user['Password']) {
    echo json_encode(['error' => 'Incorrect password']);
    exit;
}

// Save user info to session
$_SESSION['adminID']   = $user['AdminID'];
$_SESSION['name'] = $user['Name'];

echo json_encode([
    'success'  => true,
    'adminID'  => $user['AdminID'],
    'name'     => $user['Name']
]);

mysqli_close($conn);
?>