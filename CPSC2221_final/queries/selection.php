<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";
 
// Getting values
$MinPoints = $_POST['MinPoints'];
 
// Input validation
if (empty($MinPoints) || !is_numeric($MinPoints)) {
	echo "Error: Please enter a valid number for Minimum Ranking Points. <br>";
	echo "<a class='btn btn-secondary mt-2' href='selection.html'>Go Back</a>";
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
$query = "SELECT PlayerID, TotalWins, TotalLosses, TotalDraws, RankingPoints
          FROM Player
          WHERE RankingPoints >= '$MinPoints'";
 
echo "<h2>Selection Query Result</h2>";
echo "<b>Query: </b> SELECT * FROM Player WHERE RankingPoints >= $MinPoints <br><br>";
 
$result = $conn->query($query);
 
if ($result->num_rows > 0) {
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
	echo "0 results found.";
}
 
$conn->close();
 
echo "</div><br><a class='btn btn-secondary' href='selection.html'>Search Again</a> | <a href='../index.php'>Main Menu</a>";
?>
