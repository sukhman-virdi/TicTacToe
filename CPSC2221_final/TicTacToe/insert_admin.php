<?php
include 'db.php';

$username  = $_POST['username'];
$email     = $_POST['email'];
$level     = $_POST['level'];
$password  = $_POST['password'];

// Check if username or email already exists
$check = mysqli_prepare($conn, "SELECT AdminID FROM Admin WHERE Name = ? OR Email = ?");
mysqli_stmt_bind_param($check, 'ss', $username, $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    echo json_encode(['error' => 'Adminname or email already taken']);
    exit;
}

// Get next available UserID
$result = mysqli_query($conn, "SELECT MAX(AdminID) AS maxID FROM Admin");
$row    = mysqli_fetch_assoc($result);
$newID  = ($row['maxID'] ?? 0) + 1;

// Insert into GameUser
// Note: your GameUser table doesn't have firstname/lastname columns yet
// so we'll combine them into the Username or you can alter the table
$stmt = mysqli_prepare($conn, 
    "INSERT INTO Admin (AdminID, Name, Email, Password, level) VALUES (?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param($stmt, 'isssi', $newID, $username, $email, $password, $level);

if (mysqli_stmt_execute($stmt)) {

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'userID' => $newID]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to create account']);
}

mysqli_close($conn);
?>