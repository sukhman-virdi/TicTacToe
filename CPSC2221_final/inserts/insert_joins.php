<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

$PlayerID     = $_POST['PlayerID'];
$TournamentID = $_POST['TournamentID'];
$JoinDate     = $_POST['JoinDate'];

if (empty($PlayerID) || empty($TournamentID) || empty($JoinDate)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='insert_joins.html'>Go Back</a></div>";
	exit;
}

if (!is_numeric($PlayerID) || !is_numeric($TournamentID)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Player ID and Tournament ID must be numbers.</div>";
	echo "<a class='btn btn-secondary' href='insert_joins.html'>Go Back</a></div>";
	exit;
}

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "Connection Successful! <br>";
}

$query = "INSERT INTO Joins VALUES('$PlayerID', '$TournamentID', '$JoinDate')";

if ($conn->query($query) === TRUE) {
	echo "Player registered for tournament successfully! <br><br>";

	$result = $conn->query("SELECT gu.Username, tm.Name AS Tournament, j.JoinDate
	                         FROM Joins j
	                         JOIN GameUser gu ON j.PlayerID = gu.UserID
	                         JOIN Tournament_Managed tm ON j.TournamentID = tm.TournamentID
	                         WHERE j.PlayerID = '$PlayerID' AND j.TournamentID = '$TournamentID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Username</th><th>Tournament</th><th>Join Date</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["Tournament"] . "</td>
			<td>" . $row["JoinDate"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_joins.html'>Insert Another</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
