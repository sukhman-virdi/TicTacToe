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
 
$query = "SELECT gu.Username, a.Title, aw.AwardDate
          FROM Awarded aw
          JOIN GameUser gu ON aw.UserID = gu.UserID
          JOIN Achievement a ON aw.AchievementID = a.AchievementID
          ORDER BY aw.AwardDate DESC";
$result = $conn->query($query);
 
echo "<h2>All Awarded Achievements</h2>";
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Username</th><th>Achievement</th><th>Date Awarded</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["Title"] . "</td>
			<td>" . $row["AwardDate"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No awarded achievements found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
