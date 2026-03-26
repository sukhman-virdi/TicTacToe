<?php
session_start();
 
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting values
$Username = $_POST['Username'];
$Password = $_POST['Password'];
 
// Input validation
if (empty($Username) || empty($Password)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='signIn.html'>Go Back</a></div>";
	exit;
}
 
// Make sure role was set from index.html
if (empty($_SESSION['role'])) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Role not set. Please go back and select a role.</div>";
	echo "<a class='btn btn-secondary' href='index.html'>Go Back</a></div>";
	exit;
}
 
// Create connection
$conn = new mysqli($servername, $username, $password, $database);
 
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
 
// Check if username and password match in GameUser
$query  = "SELECT UserID, Username FROM GameUser WHERE Username = '$Username' AND Password = '$Password'";
$result = $conn->query($query);
 
if ($result->num_rows > 0) {
	// User found — store session
	$row = $result->fetch_assoc();
	$_SESSION['UserID']   = $row['UserID'];
	$_SESSION['Username'] = $row['Username'];
 
	$conn->close();
 
	// Redirect based on role already stored in session
	if ($_SESSION['role'] === "player") {
		header("Location: player.php");
		exit();
	} else if ($_SESSION['role'] === "manager") {
		header("Location: manager.php");
		exit();
	}
} else {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Incorrect username or password. Please try again.</div>";
	echo "<a class='btn btn-secondary' href='signIn.html'>Go Back</a></div>";
}
 
$conn->close();
?>
 
