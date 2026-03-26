<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
$conn = new mysqli($servername, $username, $password, $database);
 
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "Connection Successful! <br>";
}
 
$query = "SELECT MatchID, Move_Number, Position
          FROM Move_MadeIn
          ORDER BY MatchID, Move_Number";
$result = $conn->query($query);
 
echo "<h2>All Moves</h2>";
 
if ($result->num_rows > 0) {
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
	echo "No moves found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
