<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting values
$TournamentID = $_POST['TournamentID'];
 
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
 
// Construct the query
if (empty($TournamentID)) {
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
} else {
	if (!is_numeric($TournamentID)) {
		echo "Error: Tournament ID must be a number. <br>";
		echo "<a class='btn btn-secondary mt-2' href='join.html'>Go Back</a>";
		$conn->close();
		exit;
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
	          WHERE mc.TournamentID = '$TournamentID'
	          ORDER BY mc.Match_Date";
}
 
echo "<h2>Join Query Result</h2>";
echo "<b>Query: </b> Match history joining Match_Contained, Plays_inMatch, GameUser, Tournament_Managed <br><br>";
 
$result = $conn->query($query);
 
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
	echo "0 results found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='join.html'>Search Again</a> | <a href='../index.php'>Main Menu</a>";
?>
