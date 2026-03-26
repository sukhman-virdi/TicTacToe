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
 
$query = "SELECT mc.MatchID, mc.Match_Date, mc.Match_Time,
                 u1.Username AS Player1, u2.Username AS Player2,
                 u3.Username AS Winner, tm.Name AS Tournament
          FROM Match_Contained mc
          JOIN Plays_inMatch pm ON mc.MatchID = pm.MatchID
          JOIN GameUser u1 ON pm.Player1_ID = u1.UserID
          JOIN GameUser u2 ON pm.Player2_ID = u2.UserID
          LEFT JOIN GameUser u3 ON mc.Winner_ID = u3.UserID
          JOIN Tournament_Managed tm ON mc.TournamentID = tm.TournamentID
          ORDER BY mc.Match_Date";
$result = $conn->query($query);
 
echo "<h2>All Matches</h2>";
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>MatchID</th><th>Date</th><th>Time</th><th>Tournament</th><th>Player 1</th><th>Player 2</th><th>Winner</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["MatchID"] . "</td>
			<td>" . $row["Match_Date"] . "</td>
			<td>" . $row["Match_Time"] . "</td>
			<td>" . $row["Tournament"] . "</td>
			<td>" . $row["Player1"] . "</td>
			<td>" . $row["Player2"] . "</td>
			<td>" . ($row["Winner"] ?? "No winner yet") . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No matches found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
