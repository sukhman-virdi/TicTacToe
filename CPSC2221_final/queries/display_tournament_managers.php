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
 
$query = "SELECT tm.ManagerID, gu.Username, gu.Email, tm.Tournaments_Organized
          FROM TournamentManager tm
          JOIN GameUser gu ON tm.ManagerID = gu.UserID
          ORDER BY tm.Tournaments_Organized DESC";
$result = $conn->query($query);
 
echo "<h2>All Tournament Managers</h2>";
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>ManagerID</th><th>Username</th><th>Email</th><th>Tournaments Organized</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["ManagerID"] . "</td>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["Email"] . "</td>
			<td>" . $row["Tournaments_Organized"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No tournament managers found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
