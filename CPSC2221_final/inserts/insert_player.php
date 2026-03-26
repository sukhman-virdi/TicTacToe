<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting values
$PlayerID      = $_POST['PlayerID'];
$TotalWins     = $_POST['TotalWins'];
$TotalLosses   = $_POST['TotalLosses'];
$TotalDraws    = $_POST['TotalDraws'];
$RankingPoints = $_POST['RankingPoints'];
 
// Input validation
if (empty($PlayerID)) {
	echo "Error: Player ID is required. <br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_player.html'>Go Back</a>";
	exit;
}
 
if (!is_numeric($TotalWins) || !is_numeric($TotalLosses) || !is_numeric($TotalDraws) || !is_numeric($RankingPoints)) {
	echo "Error: Wins, Losses, Draws and Ranking Points must be numbers. <br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_player.html'>Go Back</a>";
	exit;
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
 
// Construct the query
$query = "INSERT INTO Player VALUES('$PlayerID', '$TotalWins', '$TotalLosses', '$TotalDraws', '$RankingPoints')";
 
// Execute the query
if ($conn->query($query) === TRUE) {
	echo "New player inserted successfully! <br><br>";
 
	// Show inserted record as table
	$result = $conn->query("SELECT * FROM Player WHERE PlayerID = '$PlayerID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>PlayerID</th><th>TotalWins</th><th>TotalLosses</th><th>TotalDraws</th><th>RankingPoints</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["PlayerID"] . "</td>
			<td>" . $row["TotalWins"] . "</td>
			<td>" . $row["TotalLosses"] . "</td>
			<td>" . $row["TotalDraws"] . "</td>
			<td>" . $row["RankingPoints"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "Error: " . $conn->error;
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='insert_player.html'>Insert Another Player</a> | <a href='../home.html'>Main Menu</a>";
?>
