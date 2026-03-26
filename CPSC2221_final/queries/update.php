<?php
session_start();
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting values
$PlayerID      = $_POST['PlayerID'];
$RankingPoints = $_POST['RankingPoints'];
 
// Input validation
if (empty($PlayerID) || !is_numeric($PlayerID)) {
	echo "Error: Please enter a valid Player ID. <br>";
	echo "<a class='btn btn-secondary mt-2' href='update.html'>Go Back</a>";
	exit;
}
 
if (empty($RankingPoints) || !is_numeric($RankingPoints)) {
	echo "Error: Ranking Points must be a valid number. <br>";
	echo "<a class='btn btn-secondary mt-2' href='update.html'>Go Back</a>";
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
 
// Check player exists and show before
$checkQuery  = "SELECT PlayerID, RankingPoints FROM Player WHERE PlayerID = '$PlayerID'";
$checkResult = $conn->query($checkQuery);
 
if ($checkResult->num_rows == 0) {
	echo "Error: No player found with PlayerID: $PlayerID <br>";
	echo "<a class='btn btn-secondary mt-2' href='update.html'>Go Back</a>";
	$conn->close();
	exit;
}
 
$player = $checkResult->fetch_assoc();
 
// Construct and execute the update query
$query = "UPDATE Player SET RankingPoints = '$RankingPoints' WHERE PlayerID = '$PlayerID'";
 
if ($conn->query($query) === TRUE) {
	echo "Ranking Points updated successfully! <br><br>";
 
	// Show before and after as table
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>PlayerID</th><th>Ranking Points Before</th><th>Ranking Points After</th></tr>";
	echo "<tr>
		<td>" . $player["PlayerID"] . "</td>
		<td>" . $player["RankingPoints"] . "</td>
		<td>" . $RankingPoints . "</td>
	</tr>";
	echo "</table>";
} else {
	echo "Error: " . $conn->error;
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='update.html'>Update Another Player</a> | <a href='../index.php'>Main Menu</a>";
?>
