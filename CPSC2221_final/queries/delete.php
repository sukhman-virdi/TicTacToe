<?php
session_start();
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting values
$UserID = $_POST['UserID'];
 
// Input validation
if (empty($UserID) || !is_numeric($UserID)) {
	echo "Error: Please enter a valid User ID. <br>";
	echo "<a class='btn btn-secondary mt-2' href='delete.html'>Go Back</a>";
	exit;
}
 
// Create connection
$conn = new mysqli($servername, $username, $password, $database);
 
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "Connection Successful! <br>";
}
 
// Check user exists
$checkQuery  = "SELECT UserID, Username, Email FROM GameUser WHERE UserID = '$UserID'";
$checkResult = $conn->query($checkQuery);
 
if ($checkResult->num_rows == 0) {
	echo "Error: No user found with UserID: $UserID <br>";
	echo "<a class='btn btn-secondary mt-2' href='delete.html'>Go Back</a>";
	$conn->close();
	exit;
}
 
// Show what will be deleted
$user = $checkResult->fetch_assoc();
echo "<h2>Deleting User</h2>";
echo "<table class='table table-bordered table-striped mt-3'>";
echo "<tr><th>UserID</th><th>Username</th><th>Email</th></tr>";
echo "<tr>
	<td>" . $user["UserID"] . "</td>
	<td>" . $user["Username"] . "</td>
	<td>" . $user["Email"] . "</td>
</tr>";
echo "</table><br>";
 
echo "The following related records will also be deleted due to CASCADE: <br>";
echo "- Player profile <br>";
echo "- Awarded records <br>";
echo "- Oversees records <br>";
echo "- Joins records <br><br>";
 
// Construct and execute the query
$query = "DELETE FROM GameUser WHERE UserID = '$UserID'";
 
if ($conn->query($query) === TRUE) {
	echo "User '$UserID' and all related records deleted successfully!";
} else {
	echo "Error: " . $conn->error;
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='delete.html'>Delete Another User</a> | <a href='../index.php'>Main Menu</a>";
?>
