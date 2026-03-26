<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
$conn = new mysqli($servername, $username, $password, $database);
 
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "Connection Successful! <br>";
}
 
$query = "SELECT a.Name AS AdminName, gu.Username, o.Admin_Comments
          FROM Oversees o
          JOIN Admin a ON o.AdminID = a.AdminID
          JOIN GameUser gu ON o.UserID = gu.UserID
          ORDER BY a.Name";
$result = $conn->query($query);
 
echo "<h2>All Oversees Records</h2>";
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Admin Name</th><th>Username</th><th>Admin Comments</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["AdminName"] . "</td>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["Admin_Comments"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No oversees records found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
