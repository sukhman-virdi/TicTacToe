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
 
$query = "SELECT a.AdminID, a.Name, a.Email, a.Password, a.Level, d.Department
          FROM Admin a
          JOIN Department d ON a.Level = d.Level
          ORDER BY a.AdminID";
$result = $conn->query($query);
 
echo "<h2>All Admins</h2>";
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>AdminID</th><th>Name</th><th>Email</th><th>Password</th><th>Level</th><th>Department</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["AdminID"] . "</td>
			<td>" . $row["Name"] . "</td>
			<td>" . $row["Email"] . "</td>
			<td>" . $row["Password"] . "</td>
			<td>" . $row["Level"] . "</td>
			<td>" . $row["Department"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No admins found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
