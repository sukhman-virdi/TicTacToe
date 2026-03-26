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
 
// Show overall average for reference
$avgQuery  = "SELECT AVG(RankingPoints) AS OverallAvg FROM Player";
$avgResult = $conn->query($avgQuery);
$avgRow    = $avgResult->fetch_assoc();
$overallAvg = round($avgRow["OverallAvg"], 2);
 
// Nested aggregation with GROUP BY
// Find difficulty levels where average player ranking is above the overall average
$query = "SELECT pm.Difficulty, AVG(p.RankingPoints) AS AvgRanking
          FROM Player p
          JOIN Joins j ON p.PlayerID = j.PlayerID
          JOIN Tournament_Managed tm ON j.TournamentID = tm.TournamentID
          JOIN PrizeMoney pm ON tm.Difficulty = pm.Difficulty
          GROUP BY pm.Difficulty
          HAVING AVG(p.RankingPoints) > (
               SELECT AVG(RankingPoints) FROM Player
          )";
 
echo "<h2>Nested Aggregation Query Result</h2>";
echo "<b>Query: </b> Find difficulty levels where average player ranking is above the overall average <br><br>";
echo "<b>Overall Average Ranking Points: </b>" . $overallAvg . "<br><br>";
 
$result = $conn->query($query);
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Difficulty Level</th><th>Average Ranking Points</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["Difficulty"] . "</td>
			<td>" . round($row["AvgRanking"], 2) . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No difficulty levels are above the overall average.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
