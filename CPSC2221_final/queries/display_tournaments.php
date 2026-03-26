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
 
$query = "SELECT tm.TournamentID, tm.Name, tm.Difficulty, pm.PrizeMoney, tm.StartDate, tm.ManagerID, tm.SupervisorID
          FROM Tournament_Managed tm
          JOIN PrizeMoney pm ON tm.Difficulty = pm.Difficulty
          ORDER BY tm.StartDate";
$result = $conn->query($query);
 
echo "<h2>All Tournaments</h2>";
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>TournamentID</th><th>Name</th><th>Difficulty</th><th>PrizeMoney</th><th>StartDate</th><th>ManagerID</th><th>SupervisorID</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["TournamentID"] . "</td>
			<td>" . $row["Name"] . "</td>
			<td>" . $row["Difficulty"] . "</td>
			<td>$" . $row["PrizeMoney"] . "</td>
			<td>" . $row["StartDate"] . "</td>
			<td>" . $row["ManagerID"] . "</td>
			<td>" . $row["SupervisorID"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No tournaments found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
