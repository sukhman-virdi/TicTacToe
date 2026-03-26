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
 
$query = "SELECT gu.Username, tm.Name AS Tournament, j.JoinDate
          FROM Joins j
          JOIN GameUser gu ON j.PlayerID = gu.UserID
          JOIN Tournament_Managed tm ON j.TournamentID = tm.TournamentID
          ORDER BY j.JoinDate DESC";
$result = $conn->query($query);
 
echo "<h2>All Tournament Joins</h2>";
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Username</th><th>Tournament</th><th>Join Date</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["Tournament"] . "</td>
			<td>" . $row["JoinDate"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No join records found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
