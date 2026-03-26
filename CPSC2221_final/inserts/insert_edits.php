<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "tictactoe";

$AdminID       = $_POST['AdminID'];
$AchievementID = $_POST['AchievementID'];
$EditDate      = $_POST['EditDate'];

if (empty($AdminID) || empty($AchievementID) || empty($EditDate)) {
	echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
	echo "<div class='container mt-4'>";
	echo "<div class='alert alert-danger'>Error: All fields are required.</div>";
	echo "<a class='btn btn-secondary' href='insert_edits.html'>Go Back</a></div>";
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

$query = "INSERT INTO Edits VALUES('$AdminID', '$AchievementID', '$EditDate')";

if ($conn->query($query) === TRUE) {
	echo "Edit record inserted successfully! <br><br>";

	$result = $conn->query("SELECT a.Name AS AdminName, ac.Title, e.EditDate
	                         FROM Edits e
	                         JOIN Admin a ON e.AdminID = a.AdminID
	                         JOIN Achievement ac ON e.AchievementID = ac.AchievementID
	                         WHERE e.AdminID = '$AdminID' AND e.AchievementID = '$AchievementID'");
	echo "<table class='table table-bordered table-striped mt-3'>";
	echo "<tr><th>Admin Name</th><th>Achievement Edited</th><th>Edit Date</th></tr>";
	while ($row = $result->fetch_assoc()) {
		echo "<tr>
			<td>" . $row["AdminName"] . "</td>
			<td>" . $row["Title"] . "</td>
			<td>" . $row["EditDate"] . "</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

$conn->close();

echo "</div><br><a class='btn btn-secondary' href='insert_edits.html'>Insert Another</a> | <a class='btn btn-secondary' href='../home.html'>Main Menu</a>";
?>
