<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

// Getting values
$AchievementID = $_POST['AchievementID'];
$Title         = $_POST['Title'];
$Description   = $_POST['Description'];

// Input validation
if (empty($AchievementID) || empty($Title) || empty($Description)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='insert_achievement.html'>Go Back</a></div>";
	exit;
}

if (!is_numeric($AchievementID)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: Achievement ID must be a number.</div>";
	echo "<a class='btn btn-secondary' href='insert_achievement.html'>Go Back</a></div>";
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
$query = "INSERT INTO Achievement VALUES('$AchievementID', '$Title', '$Description')";

// Execute the query
if ($conn->query($query) === TRUE) {
	echo "New achievement inserted successfully! <br><br>";

	// Show inserted record as table
	$result = $conn->query("SELECT * FROM Achievement WHERE AchievementID = '$AchievementID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>AchievementID</th><th>Title</th><th>Description</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["AchievementID"] . "</td>
			<td>" . $row["Title"] . "</td>
			<td>" . $row["Description"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_achievement.html'>Insert Another Achievement</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
