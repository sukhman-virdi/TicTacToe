<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting values
$UserID   = $_POST['UserID'];
$Username = $_POST['Username'];
$Email    = $_POST['Email'];
$Password = $_POST['Password'];
 
// Input validation
if (empty($UserID) || empty($Username) || empty($Email) || empty($Password)) {
	echo "Error: All fields are required. <br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_user.html'>Go Back</a>";
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
 
// Construct the query
$query = "INSERT INTO GameUser VALUES('$UserID', '$Username', '$Email', '$Password')";
 
// Execute the query
if ($conn->query($query) === TRUE) {
	echo "New user inserted successfully! <br><br>";
 
	// Show inserted record as table
	$result = $conn->query("SELECT * FROM GameUser WHERE UserID = '$UserID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>UserID</th><th>Username</th><th>Email</th><th>Password</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["UserID"] . "</td>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["Email"] . "</td>
			<td>" . $row["Password"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "Error: " . $conn->error;
}

$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='insert_user.html'>Insert Another User</a> | <a href='../home.html'>Main Menu</a>";
?>
