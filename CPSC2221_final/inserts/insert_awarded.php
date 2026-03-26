<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

$UserID        = $_POST['UserID'];
$AchievementID = $_POST['AchievementID'];
$AwardDate     = $_POST['AwardDate'];

if (empty($UserID) || empty($AchievementID) || empty($AwardDate)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='insert_awarded.html'>Go Back</a></div>";
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

$query = "INSERT INTO Awarded VALUES('$UserID', '$AchievementID', '$AwardDate')";

if ($conn->query($query) === TRUE) {
	echo "Achievement awarded successfully! <br><br>";

	$result = $conn->query("SELECT gu.Username, a.Title, aw.AwardDate
	                         FROM Awarded aw
	                         JOIN GameUser gu ON aw.UserID = gu.UserID
	                         JOIN Achievement a ON aw.AchievementID = a.AchievementID
	                         WHERE aw.UserID = '$UserID' AND aw.AchievementID = '$AchievementID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Username</th><th>Achievement</th><th>Date Awarded</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["Username"] . "</td>
			<td>" . $row["Title"] . "</td>
			<td>" . $row["AwardDate"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_awarded.html'>Award Another</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
