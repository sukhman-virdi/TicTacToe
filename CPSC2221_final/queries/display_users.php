<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
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
 
$query = "SELECT UserID, Username, Email, Password FROM GameUser";
$result = $conn->query($query);
 
echo "<h2>All Users</h2>";
 
if ($result->num_rows > 0) {
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
	echo "No users found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
