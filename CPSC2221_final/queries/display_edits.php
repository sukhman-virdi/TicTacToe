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
 
$query = "SELECT a.Name AS AdminName, ac.Title AS Achievement, e.EditDate
          FROM Edits e
          JOIN Admin a ON e.AdminID = a.AdminID
          JOIN Achievement ac ON e.AchievementID = ac.AchievementID
          ORDER BY e.EditDate DESC";
$result = $conn->query($query);
 
echo "<h2>All Achievement Edits</h2>";
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Admin Name</th><th>Achievement Edited</th><th>Edit Date</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["AdminName"] . "</td>
			<td>" . $row["Achievement"] . "</td>
			<td>" . $row["EditDate"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No edits found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
