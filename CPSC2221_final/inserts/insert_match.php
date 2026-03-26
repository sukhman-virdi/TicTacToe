<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

// Getting values
$MatchID      = $_POST['MatchID'];
$Match_Date   = $_POST['Match_Date'];
$Match_Time   = $_POST['Match_Time'];
$Winner_ID    = $_POST['Winner_ID'];
$TournamentID = $_POST['TournamentID'];
$Player1_ID   = $_POST['Player1_ID'];
$Player2_ID   = $_POST['Player2_ID'];

// Input validation
if (empty($MatchID) || empty($Match_Date) || empty($Match_Time) || empty($TournamentID) || empty($Player1_ID) || empty($Player2_ID)) {
	echo "Error: All fields except Winner ID are required. <br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_match.html'>Go Back</a>";
	exit;
}

if ($Player1_ID === $Player2_ID) {
	echo "Error: Player 1 and Player 2 must be different. <br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_match.html'>Go Back</a>";
	exit;
}

// Handle optional Winner_ID
if (empty($Winner_ID)) {
	$Winner_ID = "NULL";
} else {
	$Winner_ID = "'$Winner_ID'";
}

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

// Insert into Match_Contained
$query1 = "INSERT INTO Match_Contained VALUES('$MatchID', '$Match_Date', '$Match_Time', $Winner_ID, '$TournamentID')";

if ($conn->query($query1) === TRUE) {
	echo "Match record inserted successfully! <br>";
} else {
	echo "Error inserting match: " . $conn->error . "<br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_match.html'>Go Back</a>";
	$conn->close();
	exit;
}

// Insert into Plays_inMatch
$query2 = "INSERT INTO Plays_inMatch VALUES('$MatchID', '$Player1_ID', '$Player2_ID')";

if ($conn->query($query2) === TRUE) {
	echo "Players assigned to match successfully! <br><br>";
} else {
	echo "Error assigning players: " . $conn->error . "<br>";
}

// Show inserted match as table
$result = $conn->query("SELECT mc.MatchID, mc.Match_Date, mc.Match_Time, mc.Winner_ID, mc.TournamentID,
                               pm.Player1_ID, pm.Player2_ID
                        FROM Match_Contained mc
                        JOIN Plays_inMatch pm ON mc.MatchID = pm.MatchID
                        WHERE mc.MatchID = '$MatchID'");

echo "<table class='table table-bordered table-striped mt-3'>";
echo "<tr><th>MatchID</th><th>Date</th><th>Time</th><th>Winner_ID</th><th>TournamentID</th><th>Player1_ID</th><th>Player2_ID</th></tr>";
while ($row = $result->fetch_assoc()) {
	echo "<tr>
		<td>" . $row["MatchID"] . "</td>
		<td>" . $row["Match_Date"] . "</td>
		<td>" . $row["Match_Time"] . "</td>
		<td>" . ($row["Winner_ID"] ?? "N/A") . "</td>
		<td>" . $row["TournamentID"] . "</td>
		<td>" . $row["Player1_ID"] . "</td>
		<td>" . $row["Player2_ID"] . "</td>
	</tr>";
}
echo "</table>";

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_match.html'>Insert Another Match</a> | <a href='../home.html'>Main Menu</a>";
?>
