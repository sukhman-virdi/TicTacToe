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
 
// --- Aggregation Query 1: MIN, MAX, AVG Ranking Points ---
echo "<h2>Aggregation Query 1 - Ranking Points Stats</h2>";
echo "<b>Query: </b> SELECT MIN, MAX, AVG of RankingPoints FROM Player <br><br>";
 
$query1 = "SELECT MIN(RankingPoints) AS MinPoints,
                  MAX(RankingPoints) AS MaxPoints,
                  AVG(RankingPoints) AS AvgPoints
           FROM Player";
 
$result1 = $conn->query($query1);
 
if ($result1->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Minimum Ranking Points</th><th>Maximum Ranking Points</th><th>Average Ranking Points</th></tr>";
	$row = $result1->fetch_assoc();
	echo "<tr>
		<td>" . $row["MinPoints"] . "</td>
		<td>" . $row["MaxPoints"] . "</td>
		<td>" . round($row["AvgPoints"], 2) . "</td>
	</tr>";
	echo "</table>";
} else {
	echo "0 results";
}
 
// --- Aggregation Query 2: COUNT total matches per tournament ---
echo "<br><h2>Aggregation Query 2 - Total Matches per Tournament</h2>";
echo "<b>Query: </b> SELECT Tournament, COUNT(MatchID) FROM Match_Contained GROUP BY TournamentID <br><br>";
 
$query2 = "SELECT tm.Name AS Tournament, COUNT(mc.MatchID) AS TotalMatches
           FROM Match_Contained mc
           JOIN Tournament_Managed tm ON mc.TournamentID = tm.TournamentID
           GROUP BY mc.TournamentID, tm.Name";
 
$result2 = $conn->query($query2);
 
if ($result2->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Tournament</th><th>Total Matches</th></tr>";
	while ($row = $result2->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["Tournament"] . "</td>
			<td>" . $row["TotalMatches"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "0 results";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
