<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

// Getting values
$ManagerID             = $_POST['ManagerID'];
$Tournaments_Organized = $_POST['Tournaments_Organized'];

// Input validation
if (empty($ManagerID)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Manager ID is required.</div>";
	echo "<a class='btn btn-secondary' href='insert_tournament_manager.html'>Go Back</a></div>";
	exit;
}

if (!is_numeric($ManagerID) || !is_numeric($Tournaments_Organized)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Manager ID and Tournaments Organized must be numbers.</div>";
	echo "<a class='btn btn-secondary' href='insert_tournament_manager.html'>Go Back</a></div>";
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
$query = "INSERT INTO TournamentManager VALUES('$ManagerID', '$Tournaments_Organized')";

// Execute the query
if ($conn->query($query) === TRUE) {
	echo "New tournament manager inserted successfully! <br><br>";

	// Show inserted record as table
	$result = $conn->query("SELECT tm.ManagerID, gu.Username, tm.Tournaments_Organized
	                         FROM TournamentManager tm
	                         JOIN GameUser gu ON tm.ManagerID = gu.UserID
	                         WHERE tm.ManagerID = '$ManagerID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>ManagerID</th><th>Username</th><th>Tournaments Organized</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["ManagerID"] . "</td>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["Tournaments_Organized"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_tournament_manager.html'>Insert Another Manager</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
