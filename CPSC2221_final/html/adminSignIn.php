<?php
session_start();
 
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting values
$Email    = $_POST['Email'];
$Password = $_POST['Password'];
 
// Input validation
if (empty($Email) || empty($Password)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='adminSignIn.html'>Go Back</a></div>";
	exit;
}
 
// Create connection
$conn = new mysqli($servername, $username, $password, $database);
 
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
 
// Check if email and password match in Admin table
$query  = "SELECT AdminID, Name, Email FROM Admin WHERE Email = '$Email' AND Password = '$Password'";
$result = $conn->query($query);
 
if ($result->num_rows > 0) {
	// Admin found — store session
	$row = $result->fetch_assoc();
	$_SESSION['AdminID'] = $row['AdminID'];
	$_SESSION['Name']    = $row['Name'];
	$_SESSION['role']    = 'admin';
 
	$conn->close();
 
	// Redirect to admin page
	header("Location: admin.php");
	exit();
} else {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Incorrect email or password. Please try again.</div>";
	echo "<a class='btn btn-secondary' href='adminSignIn.html'>Go Back</a></div>";
}
 
$conn->close();
?>
