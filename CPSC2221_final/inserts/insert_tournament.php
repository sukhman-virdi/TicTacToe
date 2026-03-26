<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

// Getting values
$TournamentID = $_POST['TournamentID'];
$Name         = $_POST['Name'];
$Difficulty   = $_POST['Difficulty'];
$StartDate    = $_POST['StartDate'];
$ManagerID    = $_POST['ManagerID'];
$SupervisorID = $_POST['SupervisorID'];

// Input validation
if (empty($TournamentID) || empty($Name) || empty($Difficulty) || empty($StartDate) || empty($ManagerID) || empty($SupervisorID)) {
	echo "Error: All fields are required. <br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_tournament.html'>Go Back</a>";
	exit;
}

if (!in_array($Difficulty, ['1','2','3','4','5'])) {
	echo "Error: Difficulty must be between 1 and 5. <br>";
	echo "<a class='btn btn-secondary mt-2' href='insert_tournament.html'>Go Back</a>";
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
$query = "INSERT INTO Tournament_Managed VALUES('$TournamentID', '$Name', '$Difficulty', '$StartDate', '$ManagerID', '$SupervisorID')";

// Execute the query
if ($conn->query($query) === TRUE) {
	echo "New tournament inserted successfully! <br><br>";

	// Show inserted record as table
	$result = $conn->query("SELECT * FROM Tournament_Managed WHERE TournamentID = '$TournamentID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>TournamentID</th><th>Name</th><th>Difficulty</th><th>StartDate</th><th>ManagerID</th><th>SupervisorID</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["TournamentID"] . "</td>
			<td>" . $row["Name"] . "</td>
			<td>" . $row["Difficulty"] . "</td>
			<td>" . $row["StartDate"] . "</td>
			<td>" . $row["ManagerID"] . "</td>
			<td>" . $row["SupervisorID"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "Error: " . $conn->error;
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_tournament.html'>Insert Another Tournament</a> | <a href='../home.html'>Main Menu</a>";
?>
