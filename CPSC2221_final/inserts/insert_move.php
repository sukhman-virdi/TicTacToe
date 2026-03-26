<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

// Getting values
$MatchID     = $_POST['MatchID'];
$Move_Number = $_POST['Move_Number'];
$Position    = $_POST['Position'];

// Valid positions
$validPositions = ['TopLeft', 'TopMiddle', 'TopRight',
                   'MiddleLeft', 'MiddleMiddle', 'MiddleRight',
                   'BottomLeft', 'BottomMiddle', 'BottomRight'];

// Input validation
if (empty($MatchID) || empty($Move_Number) || empty($Position)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='insert_move.html'>Go Back</a></div>";
	exit;
}

if (!is_numeric($MatchID) || !is_numeric($Move_Number)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Match ID and Move Number must be numbers.</div>";
	echo "<a class='btn btn-secondary' href='insert_move.html'>Go Back</a></div>";
	exit;
}

if (!in_array($Position, $validPositions)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Invalid position selected.</div>";
	echo "<a class='btn btn-secondary' href='insert_move.html'>Go Back</a></div>";
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
$query = "INSERT INTO Move_MadeIn VALUES('$MatchID', '$Move_Number', '$Position')";

// Execute the query
if ($conn->query($query) === TRUE) {
	echo "New move inserted successfully! <br><br>";

	// Show all moves for that match as table
	$result = $conn->query("SELECT MatchID, Move_Number, Position
	                         FROM Move_MadeIn
	                         WHERE MatchID = '$MatchID'
	                         ORDER BY Move_Number");
	echo "<b>All moves for Match $MatchID:</b><br>";
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>MatchID</th><th>Move Number</th><th>Position</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["MatchID"] . "</td>
			<td>" . $row["Move_Number"] . "</td>
			<td>" . $row["Position"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_move.html'>Insert Another Move</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
