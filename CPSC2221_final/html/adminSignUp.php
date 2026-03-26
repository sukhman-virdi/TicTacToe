<?php
session_start();
 
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting values
$AdminID  = $_POST['AdminID'];
$Name     = $_POST['Name'];
$Email    = $_POST['Email'];
$Password = $_POST['Password'];
$Level    = $_POST['Level'];
 
// Input validation
if (empty($AdminID) || empty($Name) || empty($Email) || empty($Password) || empty($Level)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='adminSignUp.html'>Go Back</a></div>";
	exit;
}
 
if (!in_array($Level, ['1', '2', '3', '4', '5'])) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Level must be between 1 and 5.</div>";
	echo "<a class='btn btn-secondary' href='adminSignUp.html'>Go Back</a></div>";
	exit;
}
 
// Create connection
$conn = new mysqli($servername, $username, $password, $database);
 
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
 
// Construct the query
$query = "INSERT INTO Admin VALUES('$AdminID', '$Name', '$Email', '$Password', '$Level')";
 
// Execute the query
if ($conn->query($query) === TRUE) {
	// Store admin info in session
	$_SESSION['AdminID']  = $AdminID;
	$_SESSION['Name']     = $Name;
	$_SESSION['role']     = 'admin';
 
	$conn->close();
 
	// Redirect to admin page
	header("Location: admin.php");
	exit();
} else {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
	echo "<a class='btn btn-secondary' href='adminSignUp.html'>Go Back</a></div>";
}
 
$conn->close();
?>
