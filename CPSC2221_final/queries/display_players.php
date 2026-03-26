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
 
$query = "SELECT p.PlayerID, gu.Username, p.TotalWins, p.TotalLosses, p.TotalDraws, p.RankingPoints
          FROM Player p
          JOIN GameUser gu ON p.PlayerID = gu.UserID
          ORDER BY p.RankingPoints DESC LIMIT 10";
$result = $conn->query($query);
 
echo "<h2>All Players</h2>";
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>PlayerID</th><th>Username</th><th>TotalWins</th><th>TotalLosses</th><th>TotalDraws</th><th>RankingPoints</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["PlayerID"] . "</td>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["TotalWins"] . "</td>
			<td>" . $row["TotalLosses"] . "</td>
			<td>" . $row["TotalDraws"] . "</td>
			<td>" . $row["RankingPoints"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No players found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
