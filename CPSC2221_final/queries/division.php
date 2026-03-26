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
 
// Division query - find players who have joined ALL tournaments
$query = "SELECT gu.UserID, gu.Username
          FROM GameUser gu
          JOIN Player p ON gu.UserID = p.PlayerID
          WHERE NOT EXISTS (
               SELECT tm.TournamentID
               FROM Tournament_Managed tm
               WHERE NOT EXISTS (
                    SELECT j.TournamentID
                    FROM Joins j
                    WHERE j.PlayerID = p.PlayerID
                    AND j.TournamentID = tm.TournamentID
               )
          )";
 
echo "<h2>Division Query Result</h2>";
echo "<b>Query: </b> Find all players who have joined every tournament <br><br>";
 
$result = $conn->query($query);
 
if ($result->num_rows > 0) {
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>UserID</th><th>Username</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["UserID"] . "</td>
			<td>" . $row["Username"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "No players have joined all tournaments.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='../index.php'>Main Menu</a>";
?>
